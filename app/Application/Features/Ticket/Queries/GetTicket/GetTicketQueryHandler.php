<?php

namespace App\Application\Features\Ticket\Queries\GetTicket;

use App\Domain\Ticket\Entities\Ticket;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;
use App\Domain\Ticket\Exceptions\TicketNotFoundException;

class GetTicketQueryHandler
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
    ) {}

    public function handle(GetTicketQuery $query): Ticket
    {
        $ticket = $this->ticketRepository->findById($query->ticketId);

        if (! $ticket) {
            throw TicketNotFoundException::withId($query->ticketId);
        }

        return $ticket;
    }
}
