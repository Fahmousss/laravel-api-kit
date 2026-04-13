<?php

namespace App\Domain\ActivityLog\Enums;

enum ActivityType: string
{
    case CREATED = "created";
    case UPDATED = "updated";
    case DELETED = "deleted";
}
