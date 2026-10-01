<?php

namespace App\Jobs;

use App\Models\TechnicalTask;
use App\Notifications\TaskDeadlineReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTaskDeadlineReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public TechnicalTask $task;

    public function __construct(TechnicalTask $task)
    {
        $this->task = $task;
    }

    public function handle(): void
    {
        $candidateUser = $this->task->application->candidate->user;

        if ($candidateUser) {
            $candidateUser->notify(new TaskDeadlineReminderNotification($this->task));
        }

        $this->task->update([
            'reminder_sent_at' => now(),
        ]);
    }
}
