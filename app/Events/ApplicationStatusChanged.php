<?php

namespace App\Events;

use App\Models\Application;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Application $application;
    public ?string $fromStatus;
    public string $toStatus;
    public ?User $changedByUser;
    public ?string $comment;

    public function __construct(
        Application $application,
        ?string $fromStatus,
        string $toStatus,
        ?User $changedByUser = null,
        ?string $comment = null
    ) {
        $this->application = $application;
        $this->fromStatus = $fromStatus;
        $this->toStatus = $toStatus;
        $this->changedByUser = $changedByUser;
        $this->comment = $comment;
    }
}
