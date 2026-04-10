<?php

namespace App\Application\Features\Project\Queries\ListProjects;

readonly class ListProjectsQuery
{
    public function __construct(
        public string $actorId,
        public array  $filters = [],
        public int    $perPage = 15,
        public int    $page    = 1,
    ) {}
}
