<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\RegisterUser;

use App\Application\Features\Authentication\Common\Interfaces\AuthTokenServiceInterface;
use App\Application\Features\Authentication\Common\Interfaces\VerifyEmailNotificationServiceInterface;
use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

final readonly class RegisterUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private VerifyEmailNotificationServiceInterface $notificationService,
        private AuthTokenServiceInterface $tokenService,
    ) {}

    public function handle(RegisterUserCommand $command): UserDTO
    {
        $entity = UserEntity::create(
            name: $command->name,
            email: $command->email,
            password: Hash::make($command->password),
        );

        $savedEntity = $this->userRepository->save($entity);

        $this->notificationService->sendVerificationEmail($savedEntity->id);

        $token = $this->tokenService->generateForUser($savedEntity->id);

        return new UserDTO(
            id: $savedEntity->id,
            name: $savedEntity->name,
            email: $savedEntity->email,
            emailVerifiedAt: $savedEntity->emailVerifiedAt,
            createdAt: $savedEntity->createdAt ?? now()->toIso8601String(),
            updatedAt: $savedEntity->updatedAt ?? now()->toIso8601String(),
            token: $token,
        );
    }
}
