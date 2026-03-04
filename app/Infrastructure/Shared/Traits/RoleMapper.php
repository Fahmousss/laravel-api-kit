<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Traits;

use App\Domain\Auth\Enums\Role;
use Illuminate\Database\Eloquent\Model;

trait RoleMapper
{
    /**
     * Map the roles relation array to Domain Enums securely during Entity hydration.
     */
    protected function mapCustomAttribute(Model $model, string $name, mixed $default = null): mixed
    {
        if ($name === 'roles' && $model->relationLoaded('roles')) {
            return $model->roles->map(fn ($roleModel) => Role::tryFrom($roleModel->pivot->role))->filter()->values()->all();
        }

        return $default;
    }
}
