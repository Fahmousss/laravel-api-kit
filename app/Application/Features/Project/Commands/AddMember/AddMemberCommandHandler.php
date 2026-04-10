<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Commands\AddMember;

use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Authorization\Exceptions\UnauthorizedActionException;
use App\Domain\Project\Entities\ProjectMember;
use App\Domain\Project\Exceptions\DuplicateProjectMemberException;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;

final class AddMemberCommandHandler
{
    public function __construct(
        private ProjectMemberRepositoryInterface $memberRepository,
    ) {}

    public function handle(AddMemberCommand $command): ProjectMember
    {
        $actorRole = UserRole::from($command->actorProjectRole);

        if (! $actorRole->canManageMembers()) {
            throw UnauthorizedActionException::forAction('add project member');
        }

        $existing = $this->memberRepository->findMember($command->projectId, $command->userId);
        if ($existing) {
            throw DuplicateProjectMemberException::forUser($command->userId);
        }

        $member = new ProjectMember(
            id: null,
            projectId: $command->projectId,
            userId: $command->userId,
            role: $command->role,
            joinedAt: null,
        );

        return $this->memberRepository->save($member);
    }
}
