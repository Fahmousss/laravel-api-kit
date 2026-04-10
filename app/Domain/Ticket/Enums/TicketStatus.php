<?php

declare(strict_types=1);

namespace App\Domain\Ticket\Enums;

enum TicketStatus: string
{
    case OPEN        = 'open';
    case IN_PROGRESS = 'in_progress';
    case IN_REVIEW   = 'in_review';
    case BLOCKED     = 'blocked';
    case RESOLVED    = 'resolved';
    case REOPENED    = 'reopened';
    case CLOSED      = 'closed';
    case WONTFIX     = 'wontfix';
    case DUPLICATE   = 'duplicate';

    public function label(): string
    {
        return match ($this) {
            self::OPEN        => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::IN_REVIEW   => 'In Review',
            self::BLOCKED     => 'Blocked',
            self::RESOLVED    => 'Resolved',
            self::REOPENED    => 'Reopened',
            self::CLOSED      => 'Closed',
            self::WONTFIX     => "Won't Fix",
            self::DUPLICATE   => 'Duplicate',
        };
    }

    /**
     * Returns valid next statuses from current status
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::OPEN        => [self::IN_PROGRESS, self::WONTFIX, self::DUPLICATE],
            self::IN_PROGRESS => [self::IN_REVIEW, self::OPEN, self::BLOCKED],
            self::BLOCKED     => [self::IN_PROGRESS, self::OPEN],
            self::IN_REVIEW   => [self::RESOLVED, self::REOPENED],
            self::RESOLVED    => [self::CLOSED, self::REOPENED],
            self::REOPENED    => [self::IN_PROGRESS, self::WONTFIX],
            self::CLOSED      => [self::REOPENED],  // admin only, enforced in domain
            self::WONTFIX     => [],
            self::DUPLICATE   => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions());
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::CLOSED, self::WONTFIX, self::DUPLICATE]);
    }
}
