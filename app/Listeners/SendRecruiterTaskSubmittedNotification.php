<?php

namespace App\Listeners;

use App\Events\TaskSubmitted;
use App\Notifications\TaskSubmittedNotification;

class SendRecruiterTaskSubmittedNotification
{
    /**
     * Handle the event.
     */
    public function handle(TaskSubmitted $event): void
    {
        $recruiter = $event->task->assignedByUser;
        if ($recruiter) {
            $recruiter->notify(new TaskSubmittedNotification($event->task, $event->submission));
        }
    }
}
