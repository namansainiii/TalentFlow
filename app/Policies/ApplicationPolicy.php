<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isRecruiter() || $user->isCandidate();
    }

    public function view(User $user, Application $application): bool
    {
        if ($user->isRecruiter()) {
            return true;
        }

        return $user->candidate && $user->candidate->id === $application->candidate_id;
    }

    public function updateStatus(User $user, Application $application): bool
    {
        return $user->isRecruiter();
    }
}
