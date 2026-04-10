<?php

namespace App\Infrastructure\ActivityLog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ActivityLog extends Model
{
    use HasUuids;

    protected $table      = 'activity_logs';
    public    $timestamps = false;

    protected $fillable = [
        'ticket_id', 'actor_id', 'action', 'payload', 'created_at',
    ];

    protected $casts = [
        'payload'    => 'array',
        'created_at' => 'datetime',
    ];
}
