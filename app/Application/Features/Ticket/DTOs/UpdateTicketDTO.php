<?php

namespace App\Application\Features\Ticket\DTOs;

use App\Domain\Ticket\Enums\TicketPriority;

readonly class UpdateTicketDTO
{
    public function __construct(
        public string         $ticketId,
        public string         $actorId,
        public ?string        $title,
        public ?string        $description,
        public ?TicketPriority $priority,
        public ?string        $assigneeId,
        public ?string        $dueDate,
    ) {}
}
