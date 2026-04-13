<?php

declare(strict_types=1);

namespace App\Infrastructure\Authentication\Services;

use App\Application\Features\Authentication\Common\Interfaces\PasswordResetServiceInterface;
use App\Application\Features\Authentication\DTOs\PasswordResetStatusDTO;
use App\Infrastructure\Authentication\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;

final class PasswordResetService implements PasswordResetServiceInterface
{
    public function sendResetLink(string $email): PasswordResetStatusDTO
    {
        $status = Password::sendResetLink(['email' => $email]);

        return $status === Password::RESET_LINK_SENT
            ? PasswordResetStatusDTO::success('Password reset link sent to your email')
            : PasswordResetStatusDTO::failure('Unable to send reset link');
    }

    public function resetPassword(string $email, string $password, string $passwordConfirmation, string $token): PasswordResetStatusDTO
    {
        $status = Password::reset(
            ['email' => $email, 'password' => $password, 'password_confirmation' => $passwordConfirmation, 'token' => $token],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        return match ($status) {
            Password::PASSWORD_RESET => PasswordResetStatusDTO::success('Password reset successfully'),
            Password::INVALID_TOKEN  => PasswordResetStatusDTO::failure('Invalid or expired reset token'),
            Password::INVALID_USER   => PasswordResetStatusDTO::failure('User not found'),
            default                  => PasswordResetStatusDTO::failure('Unable to reset password'),
        };
    }
}

