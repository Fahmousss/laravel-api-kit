<?php

namespace App\Domain\Ticket\Repositories;

use App\Domain\Ticket\Entities\Ticket;
use App\Domain\Shared\Pagination\PaginatedResult;

interface TicketRepositoryInterface
{
    public function findById(string $id): ?Ticket;
    public function findByProjectAndNumber(string $projectId, int $number): ?Ticket;
    public function save(Ticket $ticket): Ticket;
    public function delete(string $id): void;
    public function nextTicketNumber(string $projectId): int;

    /** @return PaginatedResult<Ticket> */
    public function paginate(
        string  $projectId,
        array   $filters,
        int     $perPage,
        int     $page
    ): PaginatedResult;
}
