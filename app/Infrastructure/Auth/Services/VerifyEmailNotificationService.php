<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Application\Auth\Common\Interfaces\VerifyEmailNotificationServiceInterface;
use App\Infrastructure\Auth\Models\User;

final class VerifyEmailNotificationService implements VerifyEmailNotificationServiceInterface
{
    public function sendVerificationEmail(int $userId): void
    {
        /** @var User $user */
        $user = User::query()->findOrFail($userId);

        $user->sendEmailVerificationNotification();
    }
}
