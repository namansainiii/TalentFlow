<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationResource;
use App\Http\Resources\CandidateResource;
use App\Http\Resources\InterviewResource;
use App\Http\Resources\JobResource;
use App\Http\Resources\TechnicalTaskResource;
use App\Http\Resources\UserResource;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\Job;
use App\Models\TechnicalTask;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    /**
     * Bootstrap workspace data for the authenticated user.
     */
    public function bootstrap(Request $request, AnalyticsService $analyticsService): JsonResponse
    {
        $user = $request->user()->loadMissing(['role', 'candidate']);
        $isCandidate = $user->isCandidate() && ! $user->isRecruiter() && ! $user->isAdmin();
        $candidateId = $user->candidate?->id ?? 0;

        // 1. Jobs with skills and application count
        $jobs = Job::with('skills')
            ->withCount('applications')
            ->latest()
            ->get();

        // 2. Candidates
        $candidatesQuery = Candidate::with(['latestResume', 'resumes']);
        if ($isCandidate) {
            $candidatesQuery->where('id', $candidateId);
        }
        $candidates = $candidatesQuery->latest()->get();

        // 3. Applications with candidate, job, and resume
        $appsQuery = Application::with(['candidate', 'job', 'resume']);
        if ($isCandidate) {
            $appsQuery->where('candidate_id', $candidateId);
        }
        $applications = $appsQuery->latest()->get();

        // 4. Interviews with interviewer and application details
        $intQuery = Interview::with(['interviewer', 'application.candidate', 'application.job']);
        if ($isCandidate) {
            $intQuery->whereHas('application', fn ($q) => $q->where('candidate_id', $candidateId));
        }
        $interviews = $intQuery->latest('scheduled_at')->get();

        // 5. Technical Tasks with assigned user, submission, and application details
        $taskQuery = TechnicalTask::with(['assignedByUser', 'latestSubmission', 'application.candidate', 'application.job']);
        if ($isCandidate) {
            $taskQuery->whereHas('application', fn ($q) => $q->where('candidate_id', $candidateId));
        }
        $tasks = $taskQuery->latest()->get();

        // 6. Recruitment Analytics computed from loaded models
        $analytics = $analyticsService->getAnalytics($jobs, $applications, $interviews, $tasks);

        return response()->json([
            'user' => (new UserResource($user))->resolve(),
            'analytics' => $analytics,
            'jobs' => JobResource::collection($jobs)->resolve(),
            'applications' => ApplicationResource::collection($applications)->resolve(),
            'interviews' => InterviewResource::collection($interviews)->resolve(),
            'tasks' => TechnicalTaskResource::collection($tasks)->resolve(),
            'candidates' => CandidateResource::collection($candidates)->resolve(),
        ]);
    }
}
