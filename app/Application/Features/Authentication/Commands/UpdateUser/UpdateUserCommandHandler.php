<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\UpdateUser;

use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Application\Features\Authorization\Common\Interfaces\SystemRoleResolverInterface;
use App\Domain\Authentication\Exceptions\UserNotFoundException;
use App\Domain\Authentication\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

final readonly class UpdateUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface       $userRepository,
        private SystemRoleResolverInterface   $roleResolver,
    ) {}

    public function handle(UpdateUserCommand $command): UserDTO
    {
        $entity = $this->userRepository->findById($command->id);

        if ($entity === null) {
            throw new UserNotFoundException($command->id);
        }

        $name     = $command->name ?? $entity->name;
        $email    = $command->email ?? $entity->email;
        $password = $command->password ? Hash::make($command->password) : $entity->password;

        $updatedEntity = new \App\Domain\Authentication\Entities\UserEntity(
            id: $entity->id,
            name: $name,
            email: $email,
            password: $password,
            emailVerifiedAt: $entity->emailVerifiedAt,
            createdAt: $entity->createdAt,
            updatedAt: now()->toIso8601String(),
        );

        $savedEntity = $this->userRepository->save($updatedEntity);

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
