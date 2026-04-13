<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Queries\GetProject;

use App\Application\Features\Project\DTOs\ProjectDTO;
use App\Domain\Project\Exceptions\ProjectNotFoundException;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;

final class GetProjectQueryHandler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    public function handle(GetProjectQuery $query): ProjectDTO
    {
        $project = $this->projectRepository->findById($query->projectId);
        if (! $project) {
            throw ProjectNotFoundException::withId($query->projectId);
        }

        return ProjectDTO::fromEntity($project, $query->actor->projectRole);
    }
}

