<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\Job;
use App\Models\TechnicalTask;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Dashboard analytics API.
     * Returns: Total Jobs, Active Candidates, Interviews This Week, Pipeline Distribution, Average Candidate Score.
     */
    public function analytics(): JsonResponse
    {
        $totalJobs = Job::count();
        $activeJobs = Job::where('status', 'open')->count();

        // Active candidates: Candidates with applications not in terminal stages (Hired or Rejected)
        $activeCandidates = Candidate::whereHas('applications', function ($q) {
            $q->whereNotIn('status', [Application::STATUS_HIRED, Application::STATUS_REJECTED]);
        })->count();

        // Interviews this week
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $interviewsThisWeek = Interview::whereBetween('scheduled_at', [$startOfWeek, $endOfWeek])->count();

        // Pipeline distribution: counts for each stage
        $allStatuses = Application::$statuses;
        $dbDistribution = Application::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $pipelineDistribution = [];
        foreach ($allStatuses as $st) {
            $pipelineDistribution[$st] = $dbDistribution[$st] ?? 0;
        }

        // Average candidate score
        $avgScore = Application::avg('skill_score');
        $averageCandidateScore = $avgScore !== null ? round((float) $avgScore, 2) : 0.0;

        return response()->json([
            'analytics' => [
                'total_jobs' => $totalJobs,
                'active_jobs' => $activeJobs,
                'active_candidates' => $activeCandidates,
                'interviews_this_week' => $interviewsThisWeek,
                'pipeline_distribution' => $pipelineDistribution,
                'average_candidate_score' => $averageCandidateScore,
                'total_applications' => Application::count(),
                'pending_tasks' => TechnicalTask::whereIn('status', [TechnicalTask::STATUS_PENDING, TechnicalTask::STATUS_IN_PROGRESS])->count(),
            ],
        ]);
    }
}
