<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Queries\ListTickets;

final readonly class ListTicketsQuery
{
    public function __construct(
        public string $projectId,
        public string $actorId,
        public array $filters = [],   // status, type, priority, assigneeId
        public int $perPage = 15,
        public int $page = 1,
    ) {}
}
