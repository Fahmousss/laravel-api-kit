<?php

declare(strict_types=1);

namespace App\Domain\Project\Enums;

enum ProjectStatus: string
{
    case ACTIVE    = 'active';
    case ON_HOLD   = 'on_hold';
    case COMPLETED = 'completed';
    case ARCHIVED  = 'archived';
}
