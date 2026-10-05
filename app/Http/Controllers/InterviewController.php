<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleInterviewRequest;
use App\Http\Requests\UpdateInterviewRequest;
use App\Http\Resources\InterviewResource;
use App\Models\Application;
use App\Models\Interview;
use App\Services\InterviewValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

class InterviewController extends Controller
{
    /**
     * List scheduled interviews with filtering.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $query = Interview::with(['interviewer', 'application.candidate', 'application.job']);

        // Candidate can only view their interviews
        if ($user->isCandidate() && ! $user->isRecruiter()) {
            if ($user->candidate) {
                $query->whereHas('application', function ($q) use ($user) {
                    $q->where('candidate_id', $user->candidate->id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('interviewer_id')) {
            $query->where('interviewer_id', $request->interviewer_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $interviews = $query->orderBy('scheduled_at')->paginate($request->input('per_page', 15));

        return InterviewResource::collection($interviews);
    }

    /**
     * Schedule a new interview for an application with conflict validation.
     */
    public function schedule(
        ScheduleInterviewRequest $request,
        Application $application,
        InterviewValidationService $validator
    ): JsonResponse {
        $interviewerId = $request->interviewer_id;
        $scheduledAt = $request->scheduled_at;

        // Conflict Validation: Interviewer check
        if ($validator->hasInterviewerConflict($interviewerId, $scheduledAt)) {
            throw ValidationException::withMessages([
                'scheduled_at' => ['The selected interviewer already has an interview scheduled within 45 minutes of this time.'],
            ]);
        }

        // Conflict Validation: Application/candidate check
        if ($validator->hasApplicationConflict($application->id, $scheduledAt)) {
            throw ValidationException::withMessages([
                'scheduled_at' => ['This candidate already has an interview scheduled within 45 minutes of this time.'],
            ]);
        }

        $interview = Interview::create([
            'application_id' => $application->id,
            'interviewer_id' => $interviewerId,
            'scheduled_at' => $scheduledAt,
            'meeting_link' => $request->meeting_link,
            'status' => $request->status ?? Interview::STATUS_SCHEDULED,
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'message' => 'Interview scheduled successfully',
            'interview' => new InterviewResource($interview->load(['interviewer', 'application.candidate', 'application.job'])),
        ], 201);
    }

    /**
     * Show interview details.
     */
    public function show(Request $request, Interview $interview): JsonResponse
    {
        $user = $request->user();
        if ($user && $user->isCandidate() && $interview->application?->candidate_id !== $user->candidate?->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $interview->load(['interviewer', 'application.candidate', 'application.job']);

        return response()->json([
            'interview' => new InterviewResource($interview),
        ]);
    }

    /**
     * Update/reschedule interview with conflict validation.
     */
    public function update(
        UpdateInterviewRequest $request,
        Interview $interview,
        InterviewValidationService $validator
    ): JsonResponse {
        $interviewerId = $request->interviewer_id ?? $interview->interviewer_id;
        $scheduledAt = $request->scheduled_at ?? $interview->scheduled_at;

        if ($request->has('scheduled_at') || $request->has('interviewer_id')) {
            if ($validator->hasInterviewerConflict($interviewerId, $scheduledAt, $interview->id)) {
                throw ValidationException::withMessages([
                    'scheduled_at' => ['The selected interviewer already has an interview scheduled within 45 minutes of this time.'],
                ]);
            }

            if ($validator->hasApplicationConflict($interview->application_id, $scheduledAt, $interview->id)) {
                throw ValidationException::withMessages([
                    'scheduled_at' => ['This candidate already has an interview scheduled within 45 minutes of this time.'],
                ]);
            }
        }

        $interview->update($request->only([
            'interviewer_id',
            'scheduled_at',
            'meeting_link',
            'status',
            'feedback',
        ]));

        return response()->json([
            'message' => 'Interview updated successfully',
            'interview' => new InterviewResource($interview->fresh(['interviewer', 'application.candidate', 'application.job'])),
        ]);
    }

    /**
     * Cancel an interview.
     */
    public function cancel(Request $request, Interview $interview): JsonResponse
    {
        $interview->update([
            'status' => Interview::STATUS_CANCELLED,
            'feedback' => $request->input('reason', 'Cancelled by recruiter'),
        ]);

        return response()->json([
            'message' => 'Interview cancelled successfully',
            'interview' => new InterviewResource($interview),
        ]);
    }

    /**
     * Complete an interview with feedback.
     */
    public function complete(Request $request, Interview $interview): JsonResponse
    {
        $request->validate([
            'feedback' => 'required|string',
        ]);

        $interview->update([
            'status' => Interview::STATUS_COMPLETED,
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'message' => 'Interview marked as completed',
            'interview' => new InterviewResource($interview),
        ]);
    }
}
