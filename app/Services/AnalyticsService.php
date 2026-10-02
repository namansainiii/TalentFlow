<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\TechnicalTask;

class AnalyticsService
{
    /**
     * Get aggregate recruitment analytics.
     */
    public function getAnalytics(): array
    {
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

        return [
            'total_jobs' => (int) ($jobStats->total ?? 0),
            'active_jobs' => (int) ($jobStats->active ?? 0),
            'active_candidates' => (int) ($appStats->active_candidates ?? 0),
            'interviews_this_week' => (int) $interviewsThisWeek,
            'pipeline_distribution' => $pipelineDistribution,
            'average_candidate_score' => $appStats->avg_score !== null ? round((float) $appStats->avg_score, 2) : 0.0,
            'total_applications' => (int) ($appStats->total ?? 0),
            'pending_tasks' => (int) $pendingTasks,
        ];
    }
}
