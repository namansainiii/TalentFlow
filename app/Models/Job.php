<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_id',
        'title',
        'department',
        'description',
        'experience',
        'salary_range',
        'application_deadline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'application_deadline' => 'date',
        ];
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_skills')
            ->withPivot('is_mandatory')
            ->withTimestamps();
    }

    public function mandatorySkills(): BelongsToMany
    {
        return $this->skills()->wherePivot('is_mandatory', true);
    }

    public function bonusSkills(): BelongsToMany
    {
        return $this->skills()->wherePivot('is_mandatory', false);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
