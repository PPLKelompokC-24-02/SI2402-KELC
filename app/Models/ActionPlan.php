<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionPlan extends Model
{
    public const STATUS_NOT_STARTED = 'Not Started';
    public const STATUS_IN_PROGRESS = 'In Progress';
    public const STATUS_COMPLETED = 'Completed';

    public const STATUSES = [
        self::STATUS_NOT_STARTED,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
    ];

    protected $fillable = [
        'user_id',
        'template_id',
        'title',
        'description',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ActionPlanTemplate::class, 'template_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isNotStarted(): bool
    {
        return $this->status === self::STATUS_NOT_STARTED;
    }

    public function isOverdue(): bool
    {
        if (! $this->due_date || $this->isCompleted()) {
            return false;
        }

        return $this->due_date->endOfDay()->isPast();
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_COMPLETED => 'status-pill--completed',
            self::STATUS_IN_PROGRESS => 'status-pill--in-progress',
            default => 'status-pill--not-started',
        };
    }
}
