<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\DeleteUser;

use App\Domain\Authentication\Exceptions\UserNotFoundException;
use App\Domain\Authentication\Repositories\UserRepositoryInterface;

final readonly class DeleteUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(DeleteUserCommand $command): void
    {
        $entity = $this->userRepository->findById($command->id);

        if ($entity === null) {
            throw new UserNotFoundException($command->id);
        }

        // Normally we'd prevent deleting oneself, but here the logic is simple enough.

        $this->userRepository->delete($command->id);
    }
}
