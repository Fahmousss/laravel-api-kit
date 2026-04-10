<?php

declare(strict_types=1);

namespace App\Infrastructure\Ticket\Models;

use App\Infrastructure\ActivityLog\Models\ActivityLogModel;
use App\Infrastructure\Comment\Models\CommentModel;
use App\Infrastructure\Project\Models\Project;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseFactory(TicketFactory::class)]
final class Ticket extends Model
{
    use HasUuids;

    protected $table = 'tickets';

    protected $fillable = [
        'project_id', 'reporter_id', 'assignee_id',
        'ticket_number', 'title', 'description',
        'type', 'status', 'priority',
        'due_date', 'resolved_at',
    ];

    protected $casts = [
        'ticket_number' => 'integer',
        'due_date'      => 'date',
        'resolved_at'   => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(CommentModel::class, 'ticket_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ActivityLogModel::class, 'ticket_id');
    }
}
