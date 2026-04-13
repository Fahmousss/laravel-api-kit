<?php

declare(strict_types=1);

namespace App\Infrastructure\Authorization\Services;

use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Application\Features\Authorization\Common\Interfaces\ActorContextResolverServiceInterface;
use App\Domain\Authorization\ValueObjects\ActorContext;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;

/**
 * Infrastructure service — permitted to use Eloquent/auth helpers.
 * Constructs a typed ActorContext from the authenticated request.
 *
 * Called by middleware and controllers. Never called from Application
 * or Domain layers.
 */
final class ActorContextResolverService implements ActorContextResolverServiceInterface
{
    public function __construct(
        private ProjectMemberRepositoryInterface $memberRepository,
    ) {}

    /**
     * Build an ActorContext with no project role.
     * Used for routes that are not project-scoped.
     */
    public function fromUser(UserDTO $user): ActorContext
    {
        return new ActorContext(
            userId:      $user->id,
            systemRole:  $user->systemRole,
            projectRole: null,
        );
    }

    /**
     * Build an ActorContext with a project-scoped role.
     * Returns null if the user is not a member of the project.
     */
    public function fromUserAndProject(UserDTO $user, string $projectId): ?ActorContext
    {
        $role = $this->memberRepository->getMemberRole($projectId, $user->id);

        if ($role === null) {
            return null;
        }

        return new ActorContext(
            userId:      $user->id,
            systemRole:  $user->systemRole,
            projectRole: $role,
        );
    }
}

