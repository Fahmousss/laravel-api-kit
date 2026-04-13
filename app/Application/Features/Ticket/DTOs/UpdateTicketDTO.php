<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\DTOs;

use App\Domain\Ticket\Enums\TicketPriority;

final readonly class UpdateTicketDTO
{
    public ?TicketPriority $priority;

    public function __construct(
        public string $ticketId,
        public ?string $title,
        public ?string $description,
        ?string $priority,
        public ?string $assigneeId,
        public ?string $dueDate,
    ) {
        $this->priority = $priority !== null ? TicketPriority::from($priority) : null;
    }
}

