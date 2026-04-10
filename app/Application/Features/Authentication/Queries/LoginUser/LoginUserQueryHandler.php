<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Queries\LoginUser;

use App\Application\Features\Authentication\Common\Interfaces\AuthTokenServiceInterface;
use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

final readonly class LoginUserQueryHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AuthTokenServiceInterface $tokenService,
    ) {}

    public function handle(LoginUserQuery $query): ?UserDTO
    {
        $entity = $this->userRepository->findByEmail($query->email);

        if (! $entity instanceof UserEntity) {
            return null;
        }

        if (! Hash::check($query->password, $entity->password)) {
            return null;
        }

        $token = $this->tokenService->generateForUser($entity->id);

        return new UserDTO(
            id: $entity->id,
            name: $entity->name,
            email: $entity->email,
            emailVerifiedAt: $entity->emailVerifiedAt,
            createdAt: $entity->createdAt ?? now()->toIso8601String(),
            updatedAt: $entity->updatedAt ?? now()->toIso8601String(),
            token: $token,
        );
    }
}
