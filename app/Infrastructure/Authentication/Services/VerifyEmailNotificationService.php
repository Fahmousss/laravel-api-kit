<?php

declare(strict_types=1);

namespace App\Infrastructure\Authentication\Services;

use App\Application\Features\Authentication\Common\Interfaces\VerifyEmailNotificationServiceInterface;
use App\Domain\Authentication\Exceptions\UserNotFoundException;
use App\Infrastructure\Authentication\Models\User;

final class VerifyEmailNotificationService implements VerifyEmailNotificationServiceInterface
{
    public function sendVerificationEmail(int $userId): void
    {
        /** @var User $user */
        $user = User::query()->find($userId);

        throw_if($user === null, UserNotFoundException::class, (string) $userId);

        $user->sendEmailVerificationNotification();
    }
}
