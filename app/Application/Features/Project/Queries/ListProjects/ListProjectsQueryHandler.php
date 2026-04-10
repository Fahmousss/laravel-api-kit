<?php

namespace App\Application\Features\Project\Queries\ListProjects;

use App\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

class ListProjectsQueryHandler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    public function handle(ListProjectsQuery $query): PaginatedResult
    {
        return $this->projectRepository->paginate(
            array_merge($query->filters, ['member_id' => $query->actorId]),
            $query->perPage,
            $query->page,
        );
    }
}
