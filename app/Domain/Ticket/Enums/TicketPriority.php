<?php

declare(strict_types=1);

namespace App\Domain\Ticket\Enums;

enum TicketPriority: string
{
    case LOW      = 'low';
    case MEDIUM   = 'medium';
    case HIGH     = 'high';
    case CRITICAL = 'critical';
}
