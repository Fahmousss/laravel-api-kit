<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Queries\ListProjects;

use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class ListProjectsQuery
{
    public function __construct(
        public ActorContext $actor,
        public array $filters = [],
        public int $perPage = 15,
        public int $page = 1,
    ) {}
}
