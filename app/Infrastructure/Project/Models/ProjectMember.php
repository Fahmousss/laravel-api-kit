<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Models;

use App\Domain\Authorization\Enums\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class ProjectMember extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $table = 'project_members';

    protected $fillable = [
        'project_id', 'user_id', 'role', 'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'role'      => UserRole::class,
    ];
}
