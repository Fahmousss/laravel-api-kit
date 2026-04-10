<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Queries\GetProjectMembers;

final class GetProjectMemberRoleQuery
{
    public function __construct(
        public string $projectId,
        public string $userId,
    ) {}
}
