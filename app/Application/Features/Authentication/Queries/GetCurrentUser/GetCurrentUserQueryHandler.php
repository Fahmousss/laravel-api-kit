<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Queries\GetCurrentUser;

use App\Application\Features\Authentication\Common\Interfaces\AuthenticatedUserContextInterface;
use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Application\Features\Authorization\Common\Interfaces\SystemRoleResolverInterface;
use App\Domain\Authentication\Entities\UserEntity;

final readonly class GetCurrentUserQueryHandler
{
    public function __construct(
        private AuthenticatedUserContextInterface $authenticatedUser,
        private SystemRoleResolverInterface       $roleResolver,
    ) {}

    public function handle(GetCurrentUserQuery $query): ?UserDTO
    {
        $entity = $this->authenticatedUser->currentUser();

        if (! $entity instanceof UserEntity) {
            return null;
        }

        return new UserDTO(
            id:              $entity->id,
            name:            $entity->name,
            email:           $entity->email,
            systemRole:      $this->roleResolver->resolveForEmail($entity->email),
            emailVerifiedAt: $entity->emailVerifiedAt,
            createdAt:       $entity->createdAt ?? now()->toIso8601String(),
            updatedAt:       $entity->updatedAt ?? now()->toIso8601String(),
        );
    }
}
