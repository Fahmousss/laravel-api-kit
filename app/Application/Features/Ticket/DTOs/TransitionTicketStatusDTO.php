<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\DTOs;

use App\Domain\Ticket\Enums\TicketStatus;

final readonly class TransitionTicketStatusDTO
{
    public TicketStatus $newStatus;

    public function __construct(
        public string $ticketId,
        string $newStatus,
        public ?string $comment = null,
    ) {
        $this->newStatus = TicketStatus::from($newStatus);
    }
}

