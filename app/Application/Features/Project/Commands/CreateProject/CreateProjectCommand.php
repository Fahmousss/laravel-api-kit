<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Commands\CreateProject;

use App\Application\Features\Project\DTOs\CreateProjectDTO;
use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class CreateProjectCommand
{
    public function __construct(
        public ActorContext $actor,
        public CreateProjectDTO $dto,
    ) {}
}

