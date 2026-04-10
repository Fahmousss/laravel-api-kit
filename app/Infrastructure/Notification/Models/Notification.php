<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class Notification extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $table = 'ticket_notifications';

    protected $fillable = [
        'user_id', 'ticket_id', 'type', 'payload', 'read', 'created_at',
    ];

    protected $casts = [
        'payload'    => 'array',
        'read'       => 'boolean',
        'created_at' => 'datetime',
    ];
}
