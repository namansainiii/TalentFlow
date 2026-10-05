<?php

namespace App\Policies;

use App\Models\Resume;
use App\Models\User;

class ResumePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Resume $resume): bool
    {
        if ($user->isRecruiter() || $user->isAdmin()) {
            return true;
        }

        return $user->candidate && $user->candidate->id === $resume->candidate_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Resume $resume): bool
    {
        if ($user->isRecruiter() || $user->isAdmin()) {
            return true;
        }

        return $user->candidate && $user->candidate->id === $resume->candidate_id;
    }

    public function delete(User $user, Resume $resume): bool
    {
        return $user->isAdmin();
    }
}
