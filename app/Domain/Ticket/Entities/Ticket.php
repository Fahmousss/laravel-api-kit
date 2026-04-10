<?php

declare(strict_types=1);

namespace App\Domain\Ticket\Entities;

use App\Domain\Ticket\Enums\TicketPriority;
use App\Domain\Ticket\Enums\TicketStatus;
use App\Domain\Ticket\Enums\TicketType;
use App\Domain\Ticket\Exceptions\InvalidStatusTransitionException;

final class Ticket
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $projectId,
        public readonly string $reporterId,
        public ?string $assigneeId,
        public readonly int $ticketNumber,
        public string $title,
        public ?string $description,
        public readonly TicketType $type,
        public TicketStatus $status,
        public TicketPriority $priority,
        public ?string $dueDate,
        public ?string $resolvedAt,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
    ) {}

    public function transitionTo(TicketStatus $newStatus): void
    {
        if (! $this->status->canTransitionTo($newStatus)) {
            throw InvalidStatusTransitionException::fromTo($this->status, $newStatus);
        }
        $this->status = $newStatus;

        if ($newStatus === TicketStatus::RESOLVED) {
            $this->resolvedAt = now()->toDateTimeString();
        }
    }

    public function assign(string $assigneeId): void
    {
        $this->assigneeId = $assigneeId;
    }

    public function isOwnedBy(string $userId): bool
    {
        return $this->reporterId === $userId;
    }

    public function isAssignedTo(string $userId): bool
    {
        return $this->assigneeId === $userId;
    }
}
