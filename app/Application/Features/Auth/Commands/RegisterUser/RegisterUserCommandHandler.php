<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

use App\Application\Auth\DTOs\UserDTO;
use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class RegisterUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(RegisterUserCommand $command): UserDTO
    {
        $entity = UserEntity::create(
            name: $command->name,
            email: $command->email,
            password: Hash::make($command->password),
        );

        $savedEntity = $this->userRepository->save($entity);

        /** @var User $model */
        $model = User::query()->findOrFail($savedEntity->id);
        $model->sendEmailVerificationNotification();

        $token = $model->createToken('auth-token')->plainTextToken;

        return new UserDTO(
            id: $savedEntity->id,
            name: $savedEntity->name,
            email: $savedEntity->email,
            emailVerifiedAt: null,
            createdAt: $model->created_at?->toIso8601String() ?? now()->toIso8601String(),
            updatedAt: $model->updated_at?->toIso8601String() ?? now()->toIso8601String(),
            token: $token,
        );
    }
}
