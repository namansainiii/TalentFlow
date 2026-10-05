<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusNotification extends Notification
{
    use Queueable;

    public Application $application;

    public string $toStatus;

    public function __construct(Application $application, string $toStatus)
    {
        $this->application = $application;
        $this->toStatus = $toStatus;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $jobTitle = $this->application->job->title ?? 'Job';

        return [
            'type' => 'application_status_updated',
            'application_id' => $this->application->id,
            'job_title' => $jobTitle,
            'status' => $this->toStatus,
            'title' => 'Application Status: '.$this->toStatus,
            'message' => 'Your application for '.$jobTitle.' has been updated to stage: '.$this->toStatus.'.',
        ];
    }
}
