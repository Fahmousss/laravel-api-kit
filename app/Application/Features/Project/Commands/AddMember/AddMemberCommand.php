<?php

namespace App\Application\Features\Project\Commands\AddMember;

use App\Domain\Authorization\Enums\UserRole;

readonly class AddMemberCommand
{
    public function __construct(
        public string   $projectId,
        public string   $actorId,
        public string   $actorProjectRole,
        public string   $userId,
        public UserRole $role,
    ) {}
}
