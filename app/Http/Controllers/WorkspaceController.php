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
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class WorkspaceController extends Controller
{
    /**
     * High-speed unified bootstrap endpoint for workspace.
     * Caches the raw JSON response to achieve sub-10ms delivery on cached reads.
     */
    public function bootstrap(Request $request): Response
    {
        $user = $request->user()->loadMissing('role', 'candidate');
        $cacheKey = "workspace_raw_json_{$user->id}";

        if ($request->boolean('fresh')) {
            Cache::forget($cacheKey);
        }

        $json = Cache::remember($cacheKey, 60, function () use ($user) {
            $isCandidate = $user->isCandidate() && !$user->isRecruiter() && !$user->isAdmin();
            $candidateId = $user->candidate?->id;

            // 1. Fetch core models with direct relations
            $jobs = Job::with(['skills', 'recruiter.role'])->latest()->get();
            $jobsById = $jobs->keyBy('id');

            $candidatesQuery = Candidate::with('latestResume');
            if ($isCandidate && $candidateId) {
                $candidatesQuery->where('id', $candidateId);
            }
            $candidates = $candidatesQuery->latest()->get();
            $candidatesById = $candidates->keyBy('id');

            // 2. Applications
            $appsQuery = Application::with('resume');
            if ($isCandidate) {
                $appsQuery->where('candidate_id', $candidateId ?? 0);
            }
            $applications = $appsQuery->latest()->get();
            $appsById = $applications->keyBy('id');

            // Link in memory (0 DB roundtrips)
            foreach ($applications as $app) {
                if ($j = $jobsById->get($app->job_id)) {
                    $app->setRelation('job', $j);
                }
                if ($c = $candidatesById->get($app->candidate_id)) {
                    $app->setRelation('candidate', $c);
                }
            }

            // 3. Interviews
            $intQuery = Interview::with('interviewer.role');
            if ($isCandidate) {
                $candidateAppIds = $applications->pluck('id')->all();
                $intQuery->whereIn('application_id', $candidateAppIds ?: [0]);
            }
            $interviews = $intQuery->latest()->get();

            // Link in memory (0 DB roundtrips)
            foreach ($interviews as $interview) {
                if ($app = $appsById->get($interview->application_id)) {
                    $interview->setRelation('application', $app);
                }
            }

            // 4. Tasks
            $taskQuery = TechnicalTask::with(['assignedByUser.role', 'latestSubmission']);
            if ($isCandidate) {
                $candidateAppIds = $applications->pluck('id')->all();
                $taskQuery->whereIn('application_id', $candidateAppIds ?: [0]);
            }
            $tasks = $taskQuery->latest()->get();

            // Link in memory (0 DB roundtrips)
            foreach ($tasks as $task) {
                if ($app = $appsById->get($task->application_id)) {
                    $task->setRelation('application', $app);
                }
            }

            // 5. Analytics (Computed 100% in memory with 0 DB queries)
            $pipelineDistribution = [];
            foreach (Application::$statuses as $st) {
                $pipelineDistribution[$st] = 0;
            }
            foreach ($applications as $app) {
                if (isset($pipelineDistribution[$app->status])) {
                    $pipelineDistribution[$app->status]++;
                }
            }

            $totalApps = $applications->count();
            $scoredApps = $applications->whereNotNull('skill_score');
            $avgScore = $scoredApps->count() > 0 ? round((float) $scoredApps->avg('skill_score'), 2) : 0.0;

            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();
            $interviewsThisWeek = $interviews->filter(function ($i) use ($startOfWeek, $endOfWeek) {
                return $i->scheduled_at && $i->scheduled_at->between($startOfWeek, $endOfWeek);
            })->count();

            $activeCandidatesCount = $applications->filter(function ($a) {
                return !in_array($a->status, [Application::STATUS_HIRED, Application::STATUS_REJECTED]);
            })->pluck('candidate_id')->unique()->count();

            $pendingTasks = $tasks->filter(function ($t) {
                return in_array($t->status, [TechnicalTask::STATUS_PENDING, TechnicalTask::STATUS_IN_PROGRESS]);
            })->count();

            $analytics = [
                'total_jobs' => $jobs->count(),
                'active_jobs' => $jobs->where('status', 'open')->count(),
                'active_candidates' => $activeCandidatesCount,
                'interviews_this_week' => $interviewsThisWeek,
                'pipeline_distribution' => $pipelineDistribution,
                'average_candidate_score' => $avgScore,
                'total_applications' => $totalApps,
                'pending_tasks' => $pendingTasks,
            ];

            return json_encode([
                'user' => (new UserResource($user))->resolve(),
                'analytics' => $analytics,
                'jobs' => JobResource::collection($jobs)->resolve(),
                'applications' => ApplicationResource::collection($applications)->resolve(),
                'interviews' => InterviewResource::collection($interviews)->resolve(),
                'tasks' => TechnicalTaskResource::collection($tasks)->resolve(),
                'candidates' => CandidateResource::collection($candidates)->resolve(),
            ]);
        });

        return response($json, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Clear all workspace bootstrap caches on any mutation.
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}
