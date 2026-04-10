<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Queries\GetTicket;

final readonly class GetTicketQuery
{
    public function __construct(
        public string $ticketId,
        public string $actorId,
    ) {}
}
