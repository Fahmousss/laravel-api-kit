<?php

declare(strict_types=1);

namespace App\Domain\Auth\Enums;

enum Role: string
{
    case ADMIN       = 'admin';
    case CADRE       = 'cadre';
    case STAKEHOLDER = 'stakeholder';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN       => 'Admin',
            self::CADRE       => 'Cadre',
            self::STAKEHOLDER => 'Stakeholder',
        };
    }
}
