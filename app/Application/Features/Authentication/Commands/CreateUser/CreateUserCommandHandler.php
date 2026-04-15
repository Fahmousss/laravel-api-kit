<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\CreateUser;

use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Application\Features\Authorization\Common\Interfaces\SystemRoleResolverInterface;
use App\Domain\Authentication\Entities\UserEntity;
use App\Domain\Authentication\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

final readonly class CreateUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface       $userRepository,
        private SystemRoleResolverInterface   $roleResolver,
    ) {}

    public function handle(CreateUserCommand $command): UserDTO
    {
        $entity = UserEntity::create(
            name: $command->name,
            email: $command->email,
            password: Hash::make($command->password),
        );

        $savedEntity = $this->userRepository->save($entity);

        return new UserDTO(
            id:              $savedEntity->id,
            name:            $savedEntity->name,
            email:           $savedEntity->email,
            systemRole:      $this->roleResolver->resolveForEmail($savedEntity->email),
            emailVerifiedAt: $savedEntity->emailVerifiedAt,
            createdAt:       $savedEntity->createdAt ?? now()->toIso8601String(),
            updatedAt:       $savedEntity->updatedAt ?? now()->toIso8601String(),
        );
    }
}
