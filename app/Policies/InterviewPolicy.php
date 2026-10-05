<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;

class InterviewPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Interview $interview): bool
    {
        if ($user->isRecruiter()) {
            return true;
        }

        return $user->candidate && $user->candidate->id === $interview->application->candidate_id;
    }

    public function create(User $user): bool
    {
        return $user->isRecruiter();
    }

    public function update(User $user, Interview $interview): bool
    {
        return $user->isRecruiter();
    }
}
