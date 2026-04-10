<?php

namespace App\Application\Features\Project\Queries\GetProjectMembers;

class GetProjectMemberRoleQuery
{
    public function __construct(
        public string $projectId,
        public string $userId,
    ) {}
}
