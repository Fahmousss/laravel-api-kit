<?php

declare(strict_types=1);

namespace App\Domain\Auth\Enums;

use App\Domain\Shared\Contracts\DomainPermission;

enum UserPermission: string implements DomainPermission
{
    // User Management
    case ManageUsers = 'user.manage_users';
    case ViewUsers   = 'user.view_users';
    case AssignRoles = 'user.assign_roles';

    // System Settings (still technically part of the core/auth framework here)
    case ManageSettings = 'system.manage_settings';
}
