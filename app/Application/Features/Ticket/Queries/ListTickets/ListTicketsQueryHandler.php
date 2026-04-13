<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Queries\ListTickets;

use App\Application\Features\Ticket\DTOs\TicketDTO;
use App\Domain\Shared\Pagination\PaginatedResult;
use App\Domain\Ticket\Entities\Ticket;
use App\Domain\Ticket\Repositories\TicketRepositoryInterface;

final class ListTicketsQueryHandler
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
    ) {}

    public function handle(ListTicketsQuery $query): PaginatedResult
    {
        $result = $this->ticketRepository->paginate(
            $query->projectId,
            $query->filters,
            $query->perPage,
            $query->page,
        );

        return new PaginatedResult(
            items: array_map(
                fn (Ticket $ticket): TicketDTO => TicketDTO::fromEntity($ticket),
                $result->items
            ),
            total: $result->total,
            perPage: $result->perPage,
            currentPage: $result->currentPage,
            lastPage: $result->lastPage,
        );
    }
}

