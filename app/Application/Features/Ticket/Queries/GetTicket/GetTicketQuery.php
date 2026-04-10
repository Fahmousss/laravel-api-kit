<?php

namespace App\Application\Features\Ticket\Queries\GetTicket;

readonly class GetTicketQuery
{
    public function __construct(
        public string $ticketId,
        public string $actorId,
    ) {}
}
