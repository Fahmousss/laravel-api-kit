<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Queries\GetUserById;

use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Application\Features\Authorization\Common\Interfaces\SystemRoleResolverInterface;
use App\Domain\Authentication\Entities\UserEntity;
use App\Domain\Authentication\Exceptions\UserNotFoundException;
use App\Domain\Authentication\Repositories\UserRepositoryInterface;

final readonly class GetUserByIdQueryHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private SystemRoleResolverInterface $roleResolver,
    ) {}

    public function handle(GetUserByIdQuery $query): UserDTO
    {
        $entity = $this->userRepository->findById(id: (string) $query->id);

        if (! $entity instanceof UserEntity) {
            throw new UserNotFoundException(identifier: (string) $query->id);
        }

        return new UserDTO(
            id: $entity->id,
            name: $entity->name,
            email: $entity->email,
            systemRole: $this->roleResolver->resolveForEmail($entity->email),
            emailVerifiedAt: $entity->emailVerifiedAt,
            createdAt: $entity->createdAt ?? now()->toIso8601String(),
            updatedAt: $entity->updatedAt ?? now()->toIso8601String(),
        );
    }
}
