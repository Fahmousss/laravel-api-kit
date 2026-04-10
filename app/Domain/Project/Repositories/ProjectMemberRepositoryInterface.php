<?php

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\ProjectMember;
use App\Domain\Authorization\Enums\UserRole;

interface ProjectMemberRepositoryInterface
{
    public function findMember(string $projectId, string $userId): ?ProjectMember;
    public function getMemberRole(string $projectId, string $userId): ?UserRole;
    public function save(ProjectMember $member): ProjectMember;
    public function remove(string $projectId, string $userId): void;

    /** @return ProjectMember[] */
    public function listMembers(string $projectId): array;
}
