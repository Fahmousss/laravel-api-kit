<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\RevokeRole;

use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Domain\Auth\Repositories\UserRepositoryInterface;

final readonly class RevokeRoleCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(RevokeRoleCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        if (! $user instanceof UserEntity) {
            throw new UserNotFoundException((string) $command->userId);
        }

        $this->userRepository->revokeRole($command->userId, $command->role);
    }
}
