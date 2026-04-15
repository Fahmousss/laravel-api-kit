<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Enums;

enum SystemRole: string
{
    case SYSTEM_ADMIN = 'system_admin';
    case MEMBER       = 'member';

    public function label(): string
    {
        return match ($this) {
            self::SYSTEM_ADMIN => 'System Administrator',
            self::MEMBER       => 'Member',
        };
    }

    public function isSystemAdmin(): bool
    {
        return $this === self::SYSTEM_ADMIN;
    }

    public function systemPermissions(): array
    {
        return [
            'can_access_admin_panel'  => $this->isSystemAdmin(),
            'can_manage_all_projects' => $this->isSystemAdmin(),
            'can_manage_all_users'    => $this->isSystemAdmin(),
            'can_view_system_stats'   => $this->isSystemAdmin(),
        ];
    }
}
