<?php

declare(strict_types=1);

namespace App\Infrastructure\ActivityLog\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class ActivityLog extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $table = 'activity_logs';

    protected $fillable = [
        'ticket_id', 'actor_id', 'action', 'payload', 'created_at',
    ];

    protected $casts = [
        'payload'    => 'array',
        'created_at' => 'datetime',
    ];
}
