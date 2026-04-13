<?php

declare(strict_types=1);

namespace App\Application\Features\ActivityLog\Queries\ListActivityLogs;

final readonly class ListActivityLogsQuery
{
    public function __construct(
        public string $ticketId,
        public int    $perPage = 20,
        public int    $page    = 1,
    ) {}
}
