<?php

namespace App\Notifications;

use App\Models\TechnicalTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskReviewedNotification extends Notification
{
    use Queueable;

    public TechnicalTask $task;

    public ?float $score;

    public ?string $feedback;

    public function __construct(TechnicalTask $task, ?float $score, ?string $feedback)
    {
        $this->task = $task;
        $this->score = $score;
        $this->feedback = $feedback;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $scoreText = $this->score !== null ? $this->score.'/100' : 'Graded';
        $feedbackSnippet = $this->feedback ? ': "'.$this->feedback.'"' : '';

        return [
            'type' => 'task_reviewed',
            'task_id' => $this->task->id,
            'title' => 'Technical Task Reviewed: '.$this->task->title,
            'score' => $this->score,
            'feedback' => $this->feedback,
            'message' => 'Your task "'.$this->task->title.'" was reviewed by the recruiter (Score: '.$scoreText.')'.$feedbackSnippet,
        ];
    }
}
