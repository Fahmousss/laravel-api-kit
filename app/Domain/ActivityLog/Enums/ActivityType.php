<?php

declare(strict_types=1);

namespace App\Domain\ActivityLog\Enums;

enum ActivityType: string
{
    case CREATED        = 'created';
    case UPDATED        = 'updated';
    case DELETED        = 'deleted';
    case STATUS_CHANGED = 'status_changed';
    case ASSIGNED       = 'assigned';
}

