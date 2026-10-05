<?php

namespace App\Notifications;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InterviewFeedbackNotification extends Notification
{
    use Queueable;

    public Interview $interview;

    public string $feedback;

    public function __construct(Interview $interview, string $feedback)
    {
        $this->interview = $interview;
        $this->feedback = $feedback;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $jobTitle = $this->interview->application?->job?->title ?? 'Interview';

        return [
            'type' => 'interview_feedback',
            'interview_id' => $this->interview->id,
            'title' => 'Interview Feedback: '.$jobTitle,
            'feedback' => $this->feedback,
            'message' => 'The recruiter provided feedback for your interview: "'.$this->feedback.'"',
        ];
    }
}
