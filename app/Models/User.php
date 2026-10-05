<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function candidate(): HasOne
    {
        return $this->hasOne(Candidate::class);
    }

    public function postedJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'recruiter_id');
    }

    public function conductedInterviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'interviewer_id');
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(TechnicalTask::class, 'assigned_by_user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isRecruiter(): bool
    {
        return $this->role?->name === 'recruiter' || $this->isAdmin();
    }

    public function isCandidate(): bool
    {
        return $this->role?->name === 'candidate';
    }

    public function hasRole(string $role): bool
    {
        return $this->role?->name === $role;
    }
}
