<?php

declare(strict_types=1);

namespace App\Infrastructure\Authentication\Services;

use App\Application\Features\Authentication\Common\Interfaces\PasswordResetServiceInterface;
use App\Infrastructure\Authentication\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;

final class PasswordResetService implements PasswordResetServiceInterface
{
    public function sendResetLink(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    public function resetPassword(string $email, string $password, string $passwordConfirmation, string $token): string
    {
        return Password::reset(
            ['email' => $email, 'password' => $password, 'password_confirmation' => $passwordConfirmation, 'token' => $token],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );
    }
}
