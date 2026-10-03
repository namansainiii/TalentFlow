<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadResumeRequest;
use App\Http\Resources\ResumeResource;
use App\Jobs\ProcessResumeJob;
use App\Models\Candidate;
use App\Models\Resume;
use App\Services\CandidateScoringService;
use App\Services\ResumeParserService;
use App\Services\WorkspaceCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * Upload candidate resume (PDF).
     */
    public function upload(UploadResumeRequest $request, ResumeParserService $parser): JsonResponse
    {
        $user = $request->user();

        // Find or create candidate
        $candidate = null;
        if ($request->filled('candidate_id')) {
            $candidate = Candidate::find($request->candidate_id);
        } elseif ($user && $user->candidate) {
            $candidate = $user->candidate;
        } else {
            $candidate = Candidate::firstOrCreate(
                ['email' => $request->email ?? ($user?->email ?? 'candidate_'.time().'@talentflow.test')],
                [
                    'user_id' => $user?->id,
                    'name' => $request->name ?? ($user?->name ?? 'Candidate'),
                    'phone' => $request->phone ?? $user?->phone,
                ]
            );
        }

        // Store file securely
        $file = $request->file('resume');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $storedPath = $file->store('resumes', 'local');

        $resume = Resume::create([
            'candidate_id' => $candidate->id,
            'file_path' => $storedPath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'status' => 'uploaded',
        ]);

        // Process via Queue (or inline if sync)
        ProcessResumeJob::dispatch($resume);

        WorkspaceCacheService::invalidateAll();

        $candidate->refresh()->load(['latestResume', 'resumes']);

        return response()->json([
            'message' => 'Resume uploaded successfully and processed',
            'resume' => new ResumeResource($resume),
            'candidate' => new CandidateResource($candidate),
        ], 201);
    }

    /**
     * View resume details and parsed data.
     */
    public function show(Request $request, Resume $resume): JsonResponse
    {
        $user = $request->user();
        if ($user->isCandidate() && ! $user->isRecruiter() && ! $user->isAdmin()) {
            if (! $user->candidate || $user->candidate->id !== $resume->candidate_id) {
                return response()->json([
                    'message' => 'Forbidden: You do not have access to this resume.',
                ], 403);
            }
        }

        return response()->json([
            'resume' => new ResumeResource($resume),
        ]);
    }

    /**
     * Manually trigger processing for a resume.
     */
    public function process(Request $request, Resume $resume, ResumeParserService $parser, CandidateScoringService $scorer): JsonResponse
    {
        $user = $request->user();
        if ($user->isCandidate() && ! $user->isRecruiter() && ! $user->isAdmin()) {
            if (! $user->candidate || $user->candidate->id !== $resume->candidate_id) {
                return response()->json([
                    'message' => 'Forbidden: You do not have access to this resume.',
                ], 403);
            }
        }

        $parser->parseResume($resume);

        // Recalculate any applications
        if ($resume->candidate) {
            foreach ($resume->candidate->applications as $application) {
                $scorer->calculateScore($application);
            }
        }

        return response()->json([
            'message' => 'Resume processed successfully',
            'resume' => new ResumeResource($resume->fresh()),
        ]);
    }
}
