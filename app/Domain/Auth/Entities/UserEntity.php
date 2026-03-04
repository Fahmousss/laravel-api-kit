<?php

declare(strict_types=1);

namespace App\Domain\Auth\Entities;

use App\Domain\Auth\Enums\Role;
use App\Domain\Shared\Contracts\DomainPermission;

/**
 * Pure domain entity — no framework dependencies.
 */
final readonly class UserEntity
{
    /**
     * @param Role[] $roles
     */
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public string $password,
        public ?string $emailVerifiedAt = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
        public array $roles = [],
    ) {}

    public static function create(string $name, string $email, string $password): self
    {
        return new self(
            id: null,
            name: $name,
            email: $email,
            password: $password,
            emailVerifiedAt: null,
            createdAt: now()->toIso8601String(),
            updatedAt: now()->toIso8601String(),
            roles: [],
        );
    }

    public function hasRole(Role $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    public function hasPermission(DomainPermission $permission): bool
    {
        foreach ($this->roles as $role) {
            if (in_array($permission, $role->permissions(), true)) {
                return true;
            }
        }

        return false;
    }
}
