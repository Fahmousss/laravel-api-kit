<?php

declare(strict_types=1);

namespace App\Domain\Project\Repositories;

use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Project\Entities\ProjectMember;

interface ProjectMemberRepositoryInterface
{
    public function findMember(string $projectId, string $userId): ?ProjectMember;

    public function getMemberRole(string $projectId, string $userId): ?UserRole;

    public function save(ProjectMember $member): ProjectMember;

    public function remove(string $projectId, string $userId): void;

    /**
     * @return ProjectMember[]
     */
    public function listMembers(string $projectId): array;
}
