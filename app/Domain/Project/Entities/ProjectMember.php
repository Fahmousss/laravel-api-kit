<?php

namespace App\Domain\Project\Entities;

use App\Domain\Authorization\Enums\UserRole;

class ProjectMember
{
    public function __construct(
        public readonly ?string  $id,
        public readonly string   $projectId,
        public readonly string   $userId,
        public UserRole          $role,
        public readonly ?string  $joinedAt,
    ) {}

    public function changeRole(UserRole $newRole): void
    {
        $this->role = $newRole;
    }
}
