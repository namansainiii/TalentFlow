<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Dashboard analytics API.
     * Returns: Total Jobs, Active Candidates, Interviews This Week, Pipeline Distribution, Average Candidate Score, etc.
     */
    public function analytics(AnalyticsService $analyticsService): JsonResponse
    {
        return response()->json([
            'analytics' => $analyticsService->getAnalytics(),
        ]);
    }
}
