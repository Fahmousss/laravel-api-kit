<?php

declare(strict_types=1);

namespace App\Application\Features\Authorization\Common\Interfaces;

use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Domain\Authorization\ValueObjects\ActorContext;


interface ActorContextResolverServiceInterface
{
    /**
     * Build an ActorContext with no project role.
     * Used for routes that are not project-scoped.
     */
    public function fromUser(UserDTO $user): ActorContext;

    /**
     * Build an ActorContext with a project-scoped role.
     * Returns null if the user is not a member of the project.
     */
    public function fromUserAndProject(UserDTO $user, string $projectId): ?ActorContext;
}
