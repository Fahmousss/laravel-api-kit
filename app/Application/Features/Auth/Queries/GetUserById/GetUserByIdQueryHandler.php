<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Queries\GetUserById;

use App\Application\Features\Auth\DTOs\UserDTO;
use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Domain\Auth\Repositories\UserRepositoryInterface;

final readonly class GetUserByIdQueryHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(GetUserByIdQuery $query): UserDTO
    {
        $entity = $this->userRepository->findById($query->id);

        if (! $entity instanceof UserEntity) {
            throw new UserNotFoundException((string) $query->id);
        }

        return new UserDTO(
            id: $entity->id,
            name: $entity->name,
            email: $entity->email,
            emailVerifiedAt: $entity->emailVerifiedAt,
            createdAt: $entity->createdAt ?? now()->toIso8601String(),
            updatedAt: $entity->updatedAt ?? now()->toIso8601String(),
        );
    }
}
