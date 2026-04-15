<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Commands\DeleteProject;

use App\Domain\Project\Exceptions\ProjectNotFoundException;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;

final readonly class DeleteProjectCommandHandler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private ProjectMemberRepositoryInterface $memberRepository,
    ) {}

    public function handle(DeleteProjectCommand $command): void
    {
        $project = $this->projectRepository->findById($command->id);

        if ($project === null) {
            throw new ProjectNotFoundException($command->id);
        }

        $command->actor->assertCan(\App\Domain\Authorization\Enums\SystemAction::DELETE_PROJECT);

        $this->projectRepository->delete($command->id);
    }
}
