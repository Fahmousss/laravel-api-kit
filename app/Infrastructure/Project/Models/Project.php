<?php

namespace App\Infrastructure\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasUuids;

    protected $table = 'projects';

    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'status',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(ProjectMemberModel::class, 'project_id');
    }
}
