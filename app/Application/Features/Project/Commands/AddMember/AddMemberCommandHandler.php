<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Commands\AddMember;

use App\Domain\Authorization\Enums\SystemAction;
use App\Domain\Project\Entities\ProjectMember;
use App\Domain\Project\Exceptions\DuplicateProjectMemberException;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;

final class AddMemberCommandHandler
{
    public function __construct(
        private ProjectMemberRepositoryInterface $memberRepository,
    ) {}

    public function handle(AddMemberCommand $command): void
    {
        $actor = $command->actor;

        $actor->assertCan(SystemAction::MANAGE_MEMBERS);

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

        $this->memberRepository->save($member);
    }
}
