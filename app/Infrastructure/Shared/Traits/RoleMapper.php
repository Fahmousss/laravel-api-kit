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
    public function mapCustomAttribute(Model $model, string $name, mixed $default = null): mixed
    {
        if ($name === 'roles') {
            if ($model->relationLoaded('roleModels')) {
                return $model->roleModels->map(fn ($roleModel) => Role::tryFrom($roleModel->role))->filter()->values()->all();
            }

            return [];
        }

        return $default;
    }
}
