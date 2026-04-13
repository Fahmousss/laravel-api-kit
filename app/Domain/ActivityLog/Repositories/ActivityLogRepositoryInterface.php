<?php

declare(strict_types=1);

namespace App\Domain\ActivityLog\Repositories;

use App\Domain\ActivityLog\Entities\ActivityLog;
use App\Domain\Shared\Pagination\PaginatedResult;

interface ActivityLogRepositoryInterface
{
    public function save(ActivityLog $log): ActivityLog;

    /**
     * @return PaginatedResult<ActivityLog>
     */
    public function paginate(string $ticketId, int $perPage, int $page): PaginatedResult;
}
