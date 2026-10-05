<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'technical_task_id',
        'repository_url',
        'notes',
        'file_path',
        'submitted_at',
        'score',
        'feedback',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'score' => 'integer',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(TechnicalTask::class, 'technical_task_id');
    }
}
