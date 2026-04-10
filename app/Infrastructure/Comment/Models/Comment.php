<?php

namespace App\Infrastructure\Comment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Comment extends Model
{
    use HasUuids;

    protected $table = 'comments';
    public    $timestamps = false;

    protected $fillable = [
        'ticket_id', 'author_id', 'body', 'is_internal', 'created_at',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'created_at'  => 'datetime',
    ];
}
