<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\VerifyEmail;

use App\Application\Features\Authentication\Common\Interfaces\UserVerifiedEventDispatcherInterface;
use App\Domain\Authentication\Repositories\UserRepositoryInterface;

final readonly class VerifyEmailCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserVerifiedEventDispatcherInterface $userVerifiedEventDispatcher
    ) {}

    public function handle(VerifyEmailCommand $command): void
    {
        $this->userRepository->markEmailAsVerified($command->userId);
        $this->userVerifiedEventDispatcher->dispatch($command->userId);
    }
}
