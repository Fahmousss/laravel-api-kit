<?php

namespace App\Application\Features\Project\Queries\GetProjectMembers;

use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;

final class GetProjectMemberRoleQueryHandler
{
    public function __construct(
        private ProjectMemberRepositoryInterface $projectMemberRepository
    ){}

    public function handle(GetProjectMemberRoleQuery $query): ?UserRole
    {
        return $this->projectMemberRepository->getMemberRole($query->projectId, $query->userId);
    }
}
