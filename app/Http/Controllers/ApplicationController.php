<?php

namespace App\Http\Controllers;

use App\Events\ApplicationStatusChanged;
use App\Http\Requests\ApplyJobRequest;
use App\Http\Requests\UpdateApplicationStatusRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationStatusHistoryResource;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Resume;
use App\Services\CandidateScoringService;
use App\Services\ResumeParserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApplicationController extends Controller
{
    /**
     * Apply for a job opening.
     */
    public function apply(
        ApplyJobRequest $request,
        Job $job,
        ResumeParserService $parser,
        CandidateScoringService $scorer
    ): JsonResponse {
        $user = $request->user();

        // 1. Resolve or create Candidate
        $candidate = null;
        if ($user && $user->candidate) {
            $candidate = $user->candidate;
        } else {
            $email = $request->email ?? $user?->email;
            $candidate = Candidate::firstOrCreate(
                ['email' => $email],
                [
                    'user_id' => $user?->id,
                    'name' => $request->name ?? ($user?->name ?? 'Applicant'),
                    'phone' => $request->phone ?? $user?->phone,
                ]
            );
        }

        // Update candidate extra fields if provided
        $candidateUpdates = [];
        if ($request->filled('experience_years')) {
            $candidateUpdates['experience_years'] = $request->experience_years;
        }
        if ($request->filled('education')) {
            $candidateUpdates['education'] = $request->education;
        }
        if ($request->filled('skills_summary')) {
            $candidateUpdates['skills_summary'] = $request->skills_summary;
        }
        if (! empty($candidateUpdates)) {
            $candidate->update($candidateUpdates);
        }

        // 2. Prevent duplicate application for the same job
        $existing = Application::where('job_id', $job->id)
            ->where('candidate_id', $candidate->id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You have already applied for this job opening.',
                'application' => new ApplicationResource($existing->load(['job', 'candidate', 'resume'])),
            ], 422);
        }

        // 3. Handle Resume (either uploaded new or existing resume_id)
        $resumeId = $request->resume_id;
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $storedPath = $file->store('resumes', 'local');
            $newResume = Resume::create([
                'candidate_id' => $candidate->id,
                'file_path' => $storedPath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'status' => 'uploaded',
            ]);

            // Parse immediately to extract details
            $parser->parseResume($newResume);
            $resumeId = $newResume->id;
        } elseif (! $resumeId && $candidate->latestResume) {
            $resumeId = $candidate->latestResume->id;
        }

        // 4. Create Application
        $application = Application::create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'resume_id' => $resumeId,
            'status' => Application::STATUS_APPLIED,
            'notes' => $request->notes,
        ]);

        // 5. Calculate Candidate Score
        $scorer->calculateScore($application);

        // 6. Dispatch status change event to log initial history
        event(new ApplicationStatusChanged(
            $application,
            null,
            Application::STATUS_APPLIED,
            $user,
            'Application submitted'
        ));

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => new ApplicationResource($application->load(['job', 'candidate', 'resume'])),
        ], 201);
    }

    /**
     * List all applications with filtering.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $query = Application::with(['job', 'candidate', 'resume']);

        // Candidates only see their own applications
        if ($user->isCandidate() && ! $user->isRecruiter()) {
            if ($user->candidate) {
                $query->where('candidate_id', $user->candidate->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('candidate_id')) {
            $query->where('candidate_id', $request->candidate_id);
        }

        $applications = $query->latest()->paginate($request->input('per_page', 15));

        return ApplicationResource::collection($applications);
    }

    /**
     * View application details.
     */
    public function show(Request $request, Application $application): JsonResponse
    {
        $user = $request->user();
        if ($user && $user->isCandidate() && $application->candidate_id !== $user->candidate?->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $application->load([
            'job',
            'candidate',
            'resume',
            'statusHistories.changedByUser',
            'interviews.interviewer',
            'technicalTasks.submissions',
        ]);

        return response()->json([
            'application' => new ApplicationResource($application),
        ]);
    }

    /**
     * Update application hiring pipeline status.
     * Applied -> Screening -> Shortlisted -> Interview -> Technical Task -> Hired / Rejected.
     */
    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): JsonResponse
    {
        $oldStatus = $application->status;
        $newStatus = $request->status;

        $application->update([
            'status' => $newStatus,
        ]);

        // Dispatches event: logs to application_status_histories & sends notification to candidate
        event(new ApplicationStatusChanged(
            $application,
            $oldStatus,
            $newStatus,
            $request->user(),
            $request->comment
        ));

        return response()->json([
            'message' => "Application status updated to {$newStatus}",
            'application' => new ApplicationResource($application->fresh(['job', 'candidate', 'statusHistories'])),
        ]);
    }

    /**
     * Get hiring pipeline status history for an application.
     */
    public function history(Application $application): JsonResponse
    {
        $histories = $application->statusHistories()->with('changedByUser')->get();

        return response()->json([
            'application_id' => $application->id,
            'current_status' => $application->status,
            'histories' => ApplicationStatusHistoryResource::collection($histories),
        ]);
    }

    /**
     * Recalculate candidate score.
     */
    public function recalculateScore(Application $application, CandidateScoringService $scorer): JsonResponse
    {
        $score = $scorer->calculateScore($application);

        return response()->json([
            'message' => 'Candidate score recalculated successfully',
            'skill_score' => $score,
            'application' => new ApplicationResource($application->fresh()),
        ]);
    }
}
