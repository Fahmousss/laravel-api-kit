<?php

declare(strict_types=1);

namespace App\Domain\Notification\Enums;

enum NotificationType: string
{
    case TICKET_CREATED        = 'ticket_created';
    case TICKET_ASSIGNED       = 'ticket_assigned';
    case TICKET_STATUS_CHANGED = 'ticket_status_changed';
    case TICKET_COMMENTED      = 'ticket_commented';
    case TICKET_MENTIONED      = 'ticket_mentioned';
    case TICKET_RESOLVED       = 'ticket_resolved';
    case PROJECT_MEMBER_ADDED  = 'project_member_added';
}
