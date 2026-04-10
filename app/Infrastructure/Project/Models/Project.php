<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Project extends Model
{
    use HasUuids;

    protected $table = 'projects';

    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'status',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(ProjectMember::class, 'project_id');
    }
}
