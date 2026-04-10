<?php

namespace App\Infrastructure\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notification extends Model
{
    use HasUuids;

    protected $table   = 'ticket_notifications';
    public    $timestamps = false;

    protected $fillable = [
        'user_id', 'ticket_id', 'type', 'payload', 'read', 'created_at',
    ];

    protected $casts = [
        'payload'    => 'array',
        'read'       => 'boolean',
        'created_at' => 'datetime',
    ];
}
