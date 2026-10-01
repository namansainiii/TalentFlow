<?php

namespace App\Events;

use App\Models\TaskSubmission;
use App\Models\TechnicalTask;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TechnicalTask $task;
    public TaskSubmission $submission;

    public function __construct(TechnicalTask $task, TaskSubmission $submission)
    {
        $this->task = $task;
        $this->submission = $submission;
    }
}
