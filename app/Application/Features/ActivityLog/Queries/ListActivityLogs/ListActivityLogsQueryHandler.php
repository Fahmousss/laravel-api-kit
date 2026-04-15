<?php

declare(strict_types=1);

namespace App\Application\Features\ActivityLog\Queries\ListActivityLogs;

use App\Application\Features\ActivityLog\DTOs\ActivityLogDTO;
use App\Domain\ActivityLog\Entities\ActivityLog;
use App\Domain\ActivityLog\Repositories\ActivityLogRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

final class ListActivityLogsQueryHandler
{
    public function __construct(
        private ActivityLogRepositoryInterface $activityLogRepository,
    ) {}

    public function handle(ListActivityLogsQuery $query): PaginatedResult
    {
        $result = $this->activityLogRepository->paginate(
            ticketId: $query->ticketId,
            perPage: $query->perPage,
            page: $query->page,
        );

        return new PaginatedResult(
            items: array_map(
                fn (ActivityLog $log): ActivityLogDTO => ActivityLogDTO::fromEntity($log),
                $result->items,
            ),
            total: $result->total,
            perPage: $result->perPage,
            currentPage: $result->currentPage,
            lastPage: $result->lastPage,
        );
    }
}
