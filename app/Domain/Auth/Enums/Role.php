<?php

declare(strict_types=1);

namespace App\Domain\Auth\Enums;

use App\Domain\Shared\Contracts\DomainPermission;

enum Role: string
{
    case Admin   = 'admin';
    case Manager = 'manager';
    case User    = 'user';

    /**
     * Define the permissions associated with each role.
     *
     * @return DomainPermission[]
     */
    public function permissions(): array
    {
        return match ($this) {
            self::Admin => [
                UserPermission::ManageUsers,
                UserPermission::ViewUsers,
                UserPermission::AssignRoles,
                UserPermission::ManageSettings,
            ],
            self::Manager => [
                UserPermission::ViewUsers,
            ],
            self::User => [],
        };
    }
}
