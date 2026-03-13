<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\LoginUser;

use App\Application\Features\Auth\Common\Interfaces\SessionServiceInterface;
use App\Application\Features\Auth\DTOs\UserDTO;
use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

final readonly class LoginUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private SessionServiceInterface $sessionService,
    ) {}

    public function handle(LoginUserCommand $command): ?UserDTO
    {
        $entity = $this->userRepository->findByEmail($command->email);

        if (! $entity instanceof UserEntity) {
            return null;
        }

        if (! Hash::check($command->password, $entity->password)) {
            return null;
        }

        // Perform session login
        $this->sessionService->loginById($entity->id, $command->remember);
        $this->sessionService->regenerate();

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
