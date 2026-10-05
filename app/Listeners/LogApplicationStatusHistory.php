<?php

namespace App\Listeners;

use App\Events\ApplicationStatusChanged;
use App\Models\ApplicationStatusHistory;

class LogApplicationStatusHistory
{
    /**
     * Handle the event.
     */
    public function handle(ApplicationStatusChanged $event): void
    {
        ApplicationStatusHistory::create([
            'application_id' => $event->application->id,
            'from_status' => $event->fromStatus,
            'to_status' => $event->toStatus,
            'changed_by_user_id' => $event->changedByUser?->id,
            'comment' => $event->comment,
        ]);
    }
}
