<?php

namespace App\Application\Features\Project\Commands\CreateProject;

use App\Application\Features\Project\DTOs\CreateProjectDTO;

readonly class CreateProjectCommand
{
    public function __construct(
        public CreateProjectDTO $dto,
    ) {}
}
