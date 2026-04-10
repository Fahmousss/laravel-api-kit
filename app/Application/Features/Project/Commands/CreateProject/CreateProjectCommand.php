<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Commands\CreateProject;

use App\Application\Features\Project\DTOs\CreateProjectDTO;

final readonly class CreateProjectCommand
{
    public function __construct(
        public CreateProjectDTO $dto,
    ) {}
}
