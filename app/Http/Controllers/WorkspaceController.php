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
use App\Services\WorkspaceCacheService;
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
        @set_time_limit(120);

        $user = $request->user()->loadMissing(['role', 'candidate']);
        $isCandidate = $user->isCandidate() && ! $user->isRecruiter() && ! $user->isAdmin();
        $candidateId = $user->candidate?->id ?? 0;

        $version = WorkspaceCacheService::getVersion();
        $cacheKey = "workspace_bootstrap_{$user->id}_v{$version}";
        if ($request->boolean('fresh')) {
            WorkspaceCacheService::invalidateAll();
            Cache::forget($cacheKey);
        }

        $data = Cache::remember($cacheKey, 300, function () use ($user, $isCandidate, $candidateId, $analyticsService) {
            // 1. Jobs with skills
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

            // 4. Interviews with interviewer
            $intQuery = Interview::with('interviewer');
            if ($isCandidate) {
                $intQuery->whereHas('application', fn ($q) => $q->where('candidate_id', $candidateId));
            }
            $interviews = $intQuery->latest('scheduled_at')->get();

            // Link already-loaded application relation in memory (zero extra SQL queries)
            $interviews->each(function ($interview) use ($applications) {
                if ($app = $applications->firstWhere('id', $interview->application_id)) {
                    $interview->setRelation('application', $app);
                }
            });

            // 5. Technical Tasks with assignedByUser & latestSubmission
            $taskQuery = TechnicalTask::with(['assignedByUser', 'latestSubmission']);
            if ($isCandidate) {
                $taskQuery->whereHas('application', fn ($q) => $q->where('candidate_id', $candidateId));
            }
            $tasks = $taskQuery->latest()->get();

            // Link already-loaded application relation in memory (zero extra SQL queries)
            $tasks->each(function ($task) use ($applications) {
                if ($app = $applications->firstWhere('id', $task->application_id)) {
                    $task->setRelation('application', $app);
                }
            });

            // 6. Analytics computed in-memory from loaded collections (0 extra queries, 0ms)
            $analytics = $analyticsService->getAnalytics($jobs, $applications, $interviews, $tasks);

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
