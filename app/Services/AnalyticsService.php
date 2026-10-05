<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\TechnicalTask;
use Illuminate\Support\Collection;

class AnalyticsService
{
    /**
     * Get aggregate recruitment analytics.
     * Can optionally accept pre-loaded collections to compute in-memory without extra SQL queries.
     *
     * @param  Collection<int, Job>|null  $jobs
     * @param  Collection<int, Application>|null  $applications
     * @param  Collection<int, Interview>|null  $interviews
     * @param  Collection<int, TechnicalTask>|null  $tasks
     * @return array<string, mixed>
     */
    public function getAnalytics(
        ?Collection $jobs = null,
        ?Collection $applications = null,
        ?Collection $interviews = null,
        ?Collection $tasks = null
    ): array {
        // Fast in-memory computation if collections are provided
        if ($jobs !== null && $applications !== null && $interviews !== null && $tasks !== null) {
            $pipelineDistribution = [];
            foreach (Application::$statuses as $status) {
                $pipelineDistribution[$status] = $applications->where('status', $status)->count();
            }

            $scoredApps = $applications->whereNotNull('skill_score');
            $avgScore = $scoredApps->count() > 0 ? round((float) $scoredApps->avg('skill_score'), 2) : 0.0;

            $activeCandidates = $applications
                ->whereNotIn('status', [Application::STATUS_HIRED, Application::STATUS_REJECTED])
                ->pluck('candidate_id')
                ->unique()
                ->count();

            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();
            $interviewsThisWeek = $interviews->filter(function ($i) use ($startOfWeek, $endOfWeek) {
                return $i->scheduled_at && $i->scheduled_at >= $startOfWeek && $i->scheduled_at <= $endOfWeek;
            })->count();

            $pendingTasks = $tasks->whereIn('status', [
                TechnicalTask::STATUS_PENDING,
                TechnicalTask::STATUS_IN_PROGRESS,
            ])->count();

            $tasksThisWeek = $tasks->filter(function ($t) use ($startOfWeek, $endOfWeek) {
                return $t->deadline && $t->deadline >= $startOfWeek && $t->deadline <= $endOfWeek;
            })->count();

            return [
                'total_jobs' => $jobs->count(),
                'active_jobs' => $jobs->where('status', 'open')->count(),
                'active_candidates' => $activeCandidates,
                'interviews_this_week' => $interviewsThisWeek,
                'tasks_this_week' => $tasksThisWeek > 0 ? $tasksThisWeek : $pendingTasks,
                'pipeline_distribution' => $pipelineDistribution,
                'average_candidate_score' => $avgScore,
                'total_applications' => $applications->count(),
                'pending_tasks' => $pendingTasks,
            ];
        }

        // 1. Job statistics
        $jobStats = Job::selectRaw("
            count(*) as total,
            count(case when status = 'open' then 1 end) as active
        ")->first();

        // 2. Application statistics (totals, average score, active candidates)
        $appStats = Application::selectRaw("
            count(*) as total,
            avg(skill_score) as avg_score,
            count(distinct case when status not in ('".Application::STATUS_HIRED."', '".Application::STATUS_REJECTED."') then candidate_id end) as active_candidates
        ")->first();

        // 3. Pipeline distribution: count applications in each stage
        $dbDistribution = Application::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $pipelineDistribution = [];
        foreach (Application::$statuses as $status) {
            $pipelineDistribution[$status] = $dbDistribution[$status] ?? 0;
        }

        // 4. Interviews scheduled for the current week
        $interviewsThisWeek = Interview::whereBetween('scheduled_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();

        // 5. Pending coding tasks
        $pendingTasks = TechnicalTask::whereIn('status', [
            TechnicalTask::STATUS_PENDING,
            TechnicalTask::STATUS_IN_PROGRESS,
        ])->count();

        // 6. Tasks due this week
        $tasksThisWeek = TechnicalTask::whereBetween('deadline', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();

        return [
            'total_jobs' => (int) ($jobStats->total ?? 0),
            'active_jobs' => (int) ($jobStats->active ?? 0),
            'active_candidates' => (int) ($appStats->active_candidates ?? 0),
            'interviews_this_week' => (int) $interviewsThisWeek,
            'tasks_this_week' => (int) ($tasksThisWeek > 0 ? $tasksThisWeek : $pendingTasks),
            'pipeline_distribution' => $pipelineDistribution,
            'average_candidate_score' => $appStats->avg_score !== null ? round((float) $appStats->avg_score, 2) : 0.0,
            'total_applications' => (int) ($appStats->total ?? 0),
            'pending_tasks' => (int) $pendingTasks,
        ];
    }
}
