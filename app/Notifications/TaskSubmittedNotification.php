<?php

namespace App\Notifications;

use App\Models\TaskSubmission;
use App\Models\TechnicalTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskSubmittedNotification extends Notification
{
    use Queueable;

    public TechnicalTask $task;

    public TaskSubmission $submission;

    public function __construct(TechnicalTask $task, TaskSubmission $submission)
    {
        $this->task = $task;
        $this->submission = $submission;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $candidateName = $this->task->application->candidate->name ?? 'Candidate';

        return [
            'type' => 'task_submitted',
            'task_id' => $this->task->id,
            'submission_id' => $this->submission->id,
            'title' => 'Technical Task Submitted',
            'message' => $candidateName.' has submitted the task "'.$this->task->title.'". Ready for review.',
            'repository_url' => $this->submission->repository_url,
            'submitted_at' => $this->submission->submitted_at->toISOString(),
        ];
    }
}
