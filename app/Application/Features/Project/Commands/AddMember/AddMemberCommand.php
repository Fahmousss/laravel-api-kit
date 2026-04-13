<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Commands\AddMember;

use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class AddMemberCommand
{
    public UserRole $role;

    public function __construct(
        public string $projectId,
        public ActorContext $actor,
        public string $userId,
        string $role,
    ) {
        $this->role = UserRole::from($role);
    }
}

