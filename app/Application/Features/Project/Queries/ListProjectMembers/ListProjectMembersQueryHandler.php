<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Queries\ListProjectMembers;

use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;

final class ListProjectMembersQueryHandler
{
    public function __construct(
        private readonly ProjectMemberRepositoryInterface $repository,
    ) {}

    /**
     * @return \App\Domain\Project\Entities\ProjectMember[]
     */
    public function handle(ListProjectMembersQuery $query): array
    {
        return $this->repository->listMembers($query->projectId);
    }
}
