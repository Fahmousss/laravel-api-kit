<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Queries\GetAuthenticatedUser;

use App\Application\Features\Auth\Common\Interfaces\SessionServiceInterface;
use App\Application\Features\Auth\DTOs\UserDTO;
use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Repositories\UserRepositoryInterface;

final readonly class GetAuthenticatedUserQueryHandler
{
    public function __construct(
        private SessionServiceInterface $sessionService,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(): ?UserDTO
    {
        $id = $this->sessionService->id();
        if ($id === null) {
            return null;
        }

        $entity = $this->userRepository->findById($id);
        if (! $entity instanceof UserEntity) {
            return null;
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
