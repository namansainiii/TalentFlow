<?php

namespace App\Listeners;

use App\Events\ApplicationStatusChanged;
use App\Notifications\ApplicationStatusNotification;

class SendApplicationStatusNotification
{
    /**
     * Handle the event.
     */
    public function handle(ApplicationStatusChanged $event): void
    {
        $candidateUser = $event->application->candidate?->user;
        if ($candidateUser) {
            $candidateUser->notify(new ApplicationStatusNotification($event->application, $event->toStatus));
        }
    }
}
