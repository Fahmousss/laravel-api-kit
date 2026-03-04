<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Common\Interfaces;

interface PasswordResetServiceInterface
{
    /**
     * Send a password reset link to the given user.
     *
     * @return string The status indicating success or failure.
     */
    public function sendResetLink(string $email): string;

    /**
     * Reset the user's password using the given token.
     *
     * @return string The status indicating success or failure.
     */
    public function resetPassword(string $email, string $password, string $passwordConfirmation, string $token): string;
}
