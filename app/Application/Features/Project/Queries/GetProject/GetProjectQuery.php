<?php

namespace App\Application\Features\Project\Queries\GetProject;

use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class GetProjectQuery
{
    public function __construct(
        public ActorContext $actor,
        public string       $projectId,
    ) {}
}
