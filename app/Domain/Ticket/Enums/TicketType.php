<?php

declare(strict_types=1);

namespace App\Domain\Ticket\Enums;

enum TicketType: string
{
    case PROGRESS  = 'progress';
    case ISSUE     = 'issue';
    case COMPLAINT = 'complaint';
    case REVISION  = 'revision';
    case REPORT    = 'report';
    case TRACKING  = 'tracking';

    public function label(): string
    {
        return match ($this) {
            self::PROGRESS  => 'Progress',
            self::ISSUE     => 'Issue',
            self::COMPLAINT => 'Complaint',
            self::REVISION  => 'Revision',
            self::REPORT    => 'Report',
            self::TRACKING  => 'Tracking',
        };
    }

    public function defaultStatus(): TicketStatus
    {
        return TicketStatus::OPEN;
    }

    /**
     * Does this type require review before resolution?
     */
    public function requiresReview(): bool
    {
        return in_array($this, [
            self::ISSUE, self::REVISION, self::REPORT,
        ]);
    }
}
