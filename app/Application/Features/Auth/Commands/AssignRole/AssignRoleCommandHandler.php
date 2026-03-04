<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\AssignRole;

use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Domain\Auth\Repositories\UserRepositoryInterface;

final readonly class AssignRoleCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(AssignRoleCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        if (! $user instanceof UserEntity) {
            throw new UserNotFoundException((string) $command->userId);
        }

        $this->userRepository->assignRole($command->userId, $command->role);
    }
}
