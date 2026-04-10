<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Queries\ListTickets;

use App\Domain\Shared\Pagination\PaginatedResult;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;

final class ListTicketsQueryHandler
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
