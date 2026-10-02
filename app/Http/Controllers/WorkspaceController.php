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
use Illuminate\Support\Facades\Cache;

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

        $cacheKey = "workspace_bootstrap_{$user->id}";
        if ($request->boolean('fresh')) {
            Cache::forget($cacheKey);
        }

        $data = Cache::remember($cacheKey, 15, function () use ($user, $isCandidate, $candidateId, $analyticsService) {
            // 1. Jobs
            $jobs = Job::with(['skills', 'recruiter.role'])
                ->withCount('applications')
                ->latest()
                ->get();

            // 2. Candidates
            $candidatesQuery = Candidate::with(['latestResume', 'applications.job']);
            if ($isCandidate) {
                $candidatesQuery->where('id', $candidateId);
            }
            $candidates = $candidatesQuery->latest()->get();

            // 3. Applications
            $appsQuery = Application::with([
                'job.skills',
                'candidate',
                'resume',
            ]);
            if ($isCandidate) {
                $appsQuery->where('candidate_id', $candidateId);
            }
            $applications = $appsQuery->latest()->get();

            // 4. Interviews
            $intQuery = Interview::with(['interviewer.role', 'application.candidate', 'application.job']);
            if ($isCandidate) {
                $intQuery->whereHas('application', fn ($q) => $q->where('candidate_id', $candidateId));
            }
            $interviews = $intQuery->latest('scheduled_at')->get();

            // 5. Technical Tasks
            $taskQuery = TechnicalTask::with([
                'assignedByUser.role',
                'latestSubmission',
                'application.candidate',
                'application.job',
            ]);
            if ($isCandidate) {
                $taskQuery->whereHas('application', fn ($q) => $q->where('candidate_id', $candidateId));
            }
            $tasks = $taskQuery->latest()->get();

            // 6. Analytics
            $analytics = $analyticsService->getAnalytics();

            return [
                'user' => (new UserResource($user))->resolve(),
                'analytics' => $analytics,
                'jobs' => JobResource::collection($jobs)->resolve(),
                'applications' => ApplicationResource::collection($applications)->resolve(),
                'interviews' => InterviewResource::collection($interviews)->resolve(),
                'tasks' => TechnicalTaskResource::collection($tasks)->resolve(),
                'candidates' => CandidateResource::collection($candidates)->resolve(),
            ];
        });

        return response()->json($data);
    }
}
