<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\TechnicalTask;
use App\Notifications\TaskDeadlineReminderNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * List current user's notifications.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // 24-hour deadline reminder check for candidate's pending tasks
        if ($user->isCandidate() && $user->candidate) {
            $candidateId = $user->candidate->id;
            $urgentTasks = TechnicalTask::whereHas('application', fn ($q) => $q->where('candidate_id', $candidateId))
                ->whereBetween('deadline', [now(), now()->addHours(24)])
                ->whereNull('reminder_sent_at')
                ->whereIn('status', [
                    TechnicalTask::STATUS_PENDING,
                    TechnicalTask::STATUS_IN_PROGRESS,
                ])
                ->get();

            foreach ($urgentTasks as $task) {
                $user->notify(new TaskDeadlineReminderNotification($task));
                $task->update(['reminder_sent_at' => now()]);
            }
        }

        $notifications = $user->notifications()->latest()->paginate(20);

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => NotificationResource::collection($notifications),
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }
}
