<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Queries\ListProjects;

final readonly class ListProjectsQuery
{
    public function __construct(
        public string $actorId,
        public array $filters = [],
        public int $perPage = 15,
        public int $page = 1,
    ) {}
}
