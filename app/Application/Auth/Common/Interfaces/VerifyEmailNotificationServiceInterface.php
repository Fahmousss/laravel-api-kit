<?php

declare(strict_types=1);

namespace App\Application\Auth\Common\Interfaces;

interface VerifyEmailNotificationServiceInterface
{
    /**
     * Send an email verification notification for the given user ID.
     */
    public function sendVerificationEmail(int $userId): void;
}
