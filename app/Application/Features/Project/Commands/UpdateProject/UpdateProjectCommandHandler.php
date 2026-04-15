<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Commands\UpdateProject;

use App\Application\Features\Project\DTOs\ProjectDTO;
use App\Domain\Authorization\Enums\SystemRole;
use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Project\Enums\ProjectStatus;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Domain\Project\Exceptions\ProjectNotFoundException;

final readonly class UpdateProjectCommandHandler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private ProjectMemberRepositoryInterface $memberRepository,
    ) {}

    public function handle(UpdateProjectCommand $command): ProjectDTO
    {
        $project = $this->projectRepository->findById($command->id);

        if ($project === null) {
            throw new ProjectNotFoundException($command->id);
        }

        // We check if the user is a system admin to allow bypass, but normally the controller should already guarantee this
        // for admin endpoints. However, if this command is reused elsewhere...
        // For now, let's just do it directly.
        $command->actor->assertCan(\App\Domain\Authorization\Enums\SystemAction::UPDATE_PROJECT);

        if ($command->name !== null) {
            $project->name = $command->name;
        }

        if ($command->description !== null) {
            $project->description = $command->description;
        }

        if ($command->status !== null) {
            $project->status = ProjectStatus::from($command->status);
        }

        $savedProject = $this->projectRepository->save($project);
        
        $roleValues = $this->memberRepository->getRolesByUser($command->actor->userId);
        $role = $roleValues[$project->id] ?? null;

        return ProjectDTO::fromEntity($savedProject, $role);
    }
}
