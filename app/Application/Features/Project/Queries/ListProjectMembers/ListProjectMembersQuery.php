<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Queries\ListProjectMembers;

final class ListProjectMembersQuery
{
    public function __construct(
        public readonly string $projectId,
    ) {}
}
