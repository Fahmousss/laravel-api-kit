<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Queries\GetRolesByUser;

use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;

final class GetRolesByUserQueryHandler
{
    public function __construct(
        private ProjectMemberRepositoryInterface $projectMemberRepository
    ) {}

    public function handle(GetRolesByUserQuery $query): array
    {
        return $this->projectMemberRepository->getRolesByUser($query->userId);
    }
}
