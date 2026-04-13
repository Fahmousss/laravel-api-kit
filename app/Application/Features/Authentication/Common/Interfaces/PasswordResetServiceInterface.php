<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Common\Interfaces;

use App\Application\Features\Authentication\DTOs\PasswordResetStatusDTO;

interface PasswordResetServiceInterface
{
    /**
     * Send a password reset link to the given user.
     */
    public function sendResetLink(string $email): PasswordResetStatusDTO;

    /**
     * Reset the user's password using the given token.
     */
    public function resetPassword(string $email, string $password, string $passwordConfirmation, string $token): PasswordResetStatusDTO;
}
