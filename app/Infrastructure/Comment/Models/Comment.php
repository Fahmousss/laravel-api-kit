<?php

declare(strict_types=1);

namespace App\Infrastructure\Comment\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class Comment extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $table = 'comments';

    protected $fillable = [
        'ticket_id', 'author_id', 'body', 'is_internal', 'created_at',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'created_at'  => 'datetime',
    ];
}
