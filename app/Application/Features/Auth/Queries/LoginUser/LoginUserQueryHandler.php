<?php

declare(strict_types=1);

namespace App\Application\Auth\Queries;

use App\Application\Auth\DTOs\UserDTO;
use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class LoginUserQueryHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
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

        /** @var User $model */
        $model = User::query()->findOrFail($entity->id);
        $token = $model->createToken('auth-token')->plainTextToken;

        return new UserDTO(
            id: $entity->id,
            name: $entity->name,
            email: $entity->email,
            emailVerifiedAt: $model->email_verified_at?->toIso8601String(),
            createdAt: $model->created_at?->toIso8601String() ?? now()->toIso8601String(),
            updatedAt: $model->updated_at?->toIso8601String() ?? now()->toIso8601String(),
            token: $token,
        );
    }
}
