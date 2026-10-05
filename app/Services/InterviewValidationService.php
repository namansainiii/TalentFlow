<?php

namespace App\Services;

use App\Models\Interview;
use Carbon\Carbon;

class InterviewValidationService
{
    /**
     * Check if an interviewer has a scheduling conflict (+/- 45 minutes).
     */
    public function hasInterviewerConflict(int $interviewerId, string|Carbon $scheduledAt, ?int $excludeInterviewId = null): bool
    {
        $time = Carbon::parse($scheduledAt);
        $windowStart = (clone $time)->subMinutes(45);
        $windowEnd = (clone $time)->addMinutes(45);

        $query = Interview::where('interviewer_id', $interviewerId)
            ->whereIn('status', [Interview::STATUS_SCHEDULED, Interview::STATUS_RESCHEDULED])
            ->whereBetween('scheduled_at', [$windowStart, $windowEnd]);

        if ($excludeInterviewId) {
            $query->where('id', '!=', $excludeInterviewId);
        }

        return $query->exists();
    }

    /**
     * Check if an application has another interview conflict at the same time.
     */
    public function hasApplicationConflict(int $applicationId, string|Carbon $scheduledAt, ?int $excludeInterviewId = null): bool
    {
        $time = Carbon::parse($scheduledAt);
        $windowStart = (clone $time)->subMinutes(45);
        $windowEnd = (clone $time)->addMinutes(45);

        $query = Interview::where('application_id', $applicationId)
            ->whereIn('status', [Interview::STATUS_SCHEDULED, Interview::STATUS_RESCHEDULED])
            ->whereBetween('scheduled_at', [$windowStart, $windowEnd]);

        if ($excludeInterviewId) {
            $query->where('id', '!=', $excludeInterviewId);
        }

        return $query->exists();
    }
}
