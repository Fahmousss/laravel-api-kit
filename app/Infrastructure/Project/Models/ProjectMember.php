<?php

namespace App\Infrastructure\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProjectMember extends Model
{
    use HasUuids;

    protected $table = 'project_members';
    public    $timestamps = false;

    protected $fillable = [
        'project_id', 'user_id', 'role', 'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];
}
