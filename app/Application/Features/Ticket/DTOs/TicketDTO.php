<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\DTOs;

use App\Domain\Ticket\Entities\Ticket;
use App\Domain\Ticket\Enums\TicketPriority;
use App\Domain\Ticket\Enums\TicketStatus;
use App\Domain\Ticket\Enums\TicketType;

final readonly class TicketDTO
{
    public function __construct(
        public string $id,
        public int $ticketNumber,
        public string $projectId,
        public string $reporterId,
        public ?string $assigneeId,
        public string $title,
        public ?string $description,
        public TicketType $type,
        public TicketStatus $status,
        public TicketPriority $priority,
        public ?string $dueDate,
        public ?string $resolvedAt,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromEntity(Ticket $ticket): self
    {
        return new self(
            id: $ticket->id,
            ticketNumber: $ticket->ticketNumber,
            projectId: $ticket->projectId,
            reporterId: $ticket->reporterId,
            assigneeId: $ticket->assigneeId,
            title: $ticket->title,
            description: $ticket->description,
            type: $ticket->type,
            status: $ticket->status,
            priority: $ticket->priority,
            dueDate: $ticket->dueDate,
            resolvedAt: $ticket->resolvedAt,
            createdAt: $ticket->createdAt ?? now()->toIso8601String(),
            updatedAt: $ticket->updatedAt ?? now()->toIso8601String(),
        );
    }
}
