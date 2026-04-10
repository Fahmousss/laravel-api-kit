<?php

namespace App\Application\Features\Ticket\Queries\ListTickets;

use App\Domain\Ticket\Repositories\TicketRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

class ListTicketsQueryHandler
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
    ) {}

    public function handle(ListTicketsQuery $query): PaginatedResult
    {
        return $this->ticketRepository->paginate(
            $query->projectId,
            $query->filters,
            $query->perPage,
            $query->page,
        );
    }
}
