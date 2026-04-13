<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Queries\GetTicket;

use App\Application\Features\Ticket\DTOs\TicketDTO;
use App\Domain\Ticket\Exceptions\TicketNotFoundException;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;

final class GetTicketQueryHandler
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
    ) {}

    public function handle(GetTicketQuery $query): TicketDTO
    {
        $ticket = $this->ticketRepository->findById($query->ticketId);

        if (! $ticket) {
            throw TicketNotFoundException::withId($query->ticketId);
        }

        return TicketDTO::fromEntity($ticket);
    }
}

