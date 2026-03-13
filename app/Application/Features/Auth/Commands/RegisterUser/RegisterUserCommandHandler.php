<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\RegisterUser;

use App\Application\Features\Auth\Common\Interfaces\SessionServiceInterface;
use App\Application\Features\Auth\Common\Interfaces\VerifyEmailNotificationServiceInterface;
use App\Application\Features\Auth\DTOs\UserDTO;
use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

final readonly class RegisterUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private VerifyEmailNotificationServiceInterface $notificationService,
        private SessionServiceInterface $sessionService,
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

        // Auto-login after registration
        $this->sessionService->loginById($savedEntity->id);
        $this->sessionService->regenerate();

        return new UserDTO(
            id: $savedEntity->id,
            name: $savedEntity->name,
            email: $savedEntity->email,
            emailVerifiedAt: $savedEntity->emailVerifiedAt,
            createdAt: $savedEntity->createdAt ?? now()->toIso8601String(),
            updatedAt: $savedEntity->updatedAt ?? now()->toIso8601String(),
        );
    }
}
