<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Commands\CreateProject;

use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Project\Entities\Project;
use App\Domain\Project\Entities\ProjectMember;
use App\Domain\Project\Enums\ProjectStatus;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;

final class CreateProjectCommandHandler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private ProjectMemberRepositoryInterface $memberRepository,
    ) {}

    public function handle(CreateProjectCommand $command): Project
    {
        $dto = $command->dto;

        $project = new Project(
            id: null,
            ownerId: $dto->ownerId,
            name: $dto->name,
            slug: $dto->slug,
            description: $dto->description,
            status: ProjectStatus::ACTIVE,
            createdAt: null,
            updatedAt: null,
        );

        $saved = $this->projectRepository->save($project);

        // Auto-add owner as admin member
        $member = new ProjectMember(
            id: null,
            projectId: $saved->id,
            userId: $dto->ownerId,
            role: UserRole::ADMIN,
            joinedAt: null,
        );
        $this->memberRepository->save($member);

        return $saved;
    }
}
