<?php

namespace App\Policies;

use App\Models\TechnicalTask;
use App\Models\User;

class TechnicalTaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TechnicalTask $task): bool
    {
        if ($user->isRecruiter()) {
            return true;
        }

        return $user->candidate && $user->candidate->id === $task->application->candidate_id;
    }

    public function create(User $user): bool
    {
        return $user->isRecruiter();
    }

    public function submit(User $user, TechnicalTask $task): bool
    {
        return $user->candidate && $user->candidate->id === $task->application->candidate_id;
    }

    public function review(User $user, TechnicalTask $task): bool
    {
        return $user->isRecruiter();
    }
}
