<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\DTOs;

use App\Domain\Ticket\Enums\TicketPriority;

final readonly class UpdateTicketDTO
{
    public function __construct(
        public string $ticketId,
        public string $actorId,
        public ?string $title,
        public ?string $description,
        public ?TicketPriority $priority,
        public ?string $assigneeId,
        public ?string $dueDate,
    ) {}
}
