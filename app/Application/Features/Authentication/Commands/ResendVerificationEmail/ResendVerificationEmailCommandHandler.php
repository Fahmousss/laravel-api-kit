<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\ResendVerificationEmail;

use App\Application\Features\Authentication\Common\Interfaces\VerifyEmailNotificationServiceInterface;
use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use InvalidArgumentException;

final readonly class ResendVerificationEmailCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private VerifyEmailNotificationServiceInterface $notificationService
    ) {}

    public function handle(ResendVerificationEmailCommand $command): void
    {
        $user = $this->userRepository->findByEmail($command->email);

        if (! $user instanceof UserEntity) {
            throw new UserNotFoundException($command->email);
        }

        throw_if($user->emailVerifiedAt !== null, InvalidArgumentException::class, 'Email already verified');

        if ($user->id !== null) {
            $this->notificationService->sendVerificationEmail($user->id);
        }
    }
}
