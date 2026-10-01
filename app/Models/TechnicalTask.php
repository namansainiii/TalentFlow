<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TechnicalTask extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'Pending';
    public const STATUS_IN_PROGRESS = 'In Progress';
    public const STATUS_SUBMITTED = 'Submitted';
    public const STATUS_REVIEWED = 'Reviewed';
    public const STATUS_OVERDUE = 'Overdue';

    public static array $statuses = [
        self::STATUS_PENDING,
        self::STATUS_IN_PROGRESS,
        self::STATUS_SUBMITTED,
        self::STATUS_REVIEWED,
        self::STATUS_OVERDUE,
    ];

    protected $fillable = [
        'application_id',
        'assigned_by_user_id',
        'title',
        'description',
        'deadline',
        'status',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'reminder_sent_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function assignedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by_user_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function latestSubmission(): HasOne
    {
        return $this->hasOne(TaskSubmission::class)->latestOfMany();
    }
}
