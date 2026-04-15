<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Queries\ListProjects;

use App\Application\Features\Project\DTOs\ProjectDTO;
use App\Domain\Project\Entities\Project;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

final class ListProjectsQueryHandler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private ProjectMemberRepositoryInterface $memberRepository,
    ) {}

    public function handle(ListProjectsQuery $query): PaginatedResult
    {
        $filters = $query->filters;
        if (!$query->actor->isSystemAdmin()) {
            $filters['member_id'] = $query->actor->userId;
        }

        $result = $this->projectRepository->paginate(
            $filters,
            $query->perPage,
            $query->page,
        );

        // Resolve all project roles for the actor in one query
        $roleMap = $this->memberRepository->getRolesByUser($query->actor->userId);

        return new PaginatedResult(
            items: array_map(
                fn (Project $project): ProjectDTO => ProjectDTO::fromEntity(
                    $project,
                    $roleMap[$project->id] ?? null,
                ),
                $result->items,
            ),
            total: $result->total,
            perPage: $result->perPage,
            currentPage: $result->currentPage,
            lastPage: $result->lastPage,
        );
    }
}

