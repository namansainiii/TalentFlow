<?php

namespace App\Notifications;

use App\Models\TechnicalTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskDeadlineReminderNotification extends Notification
{
    use Queueable;

    public TechnicalTask $task;

    public function __construct(TechnicalTask $task)
    {
        $this->task = $task;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'task_deadline_reminder',
            'task_id' => $this->task->id,
            'title' => 'Deadline Reminder: '.$this->task->title,
            'message' => 'Your technical task "'.$this->task->title.'" is due in less than 24 hours ('.$this->task->deadline->toDayDateTimeString().'). Please submit on time.',
            'deadline' => $this->task->deadline->toISOString(),
        ];
    }
}
