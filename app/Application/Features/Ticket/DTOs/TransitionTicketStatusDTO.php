<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\DTOs;

use App\Domain\Ticket\Enums\TicketStatus;

final readonly class TransitionTicketStatusDTO
{
    public function __construct(
        public string $ticketId,
        public string $actorId,
        public string $actorProjectRole,
        public TicketStatus $newStatus,
        public ?string $comment = null,
    ) {}
}
