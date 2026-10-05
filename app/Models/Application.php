<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    public const STATUS_APPLIED = 'Applied';

    public const STATUS_SCREENING = 'Screening';

    public const STATUS_SHORTLISTED = 'Shortlisted';

    public const STATUS_INTERVIEW = 'Interview';

    public const STATUS_TECHNICAL_TASK = 'Technical Task';

    public const STATUS_HIRED = 'Hired';

    public const STATUS_REJECTED = 'Rejected';

    public static array $statuses = [
        self::STATUS_APPLIED,
        self::STATUS_SCREENING,
        self::STATUS_SHORTLISTED,
        self::STATUS_INTERVIEW,
        self::STATUS_TECHNICAL_TASK,
        self::STATUS_HIRED,
        self::STATUS_REJECTED,
    ];

    protected $fillable = [
        'job_id',
        'candidate_id',
        'resume_id',
        'status',
        'skill_score',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'skill_score' => 'decimal:2',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ApplicationStatusHistory::class)->latest();
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class)->latest('scheduled_at');
    }

    public function technicalTasks(): HasMany
    {
        return $this->hasMany(TechnicalTask::class)->latest();
    }
}
