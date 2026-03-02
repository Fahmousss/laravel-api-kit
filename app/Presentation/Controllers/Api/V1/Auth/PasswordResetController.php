<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Infrastructure\Auth\Models\User;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\ForgotPasswordRequest;
use App\Presentation\Requests\Api\V1\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

final class PasswordResetController extends ApiController
{
    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return $this->success(message: 'Password reset link sent to your email');
        }

        return $this->error('Unable to send reset link', 500);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->success(message: 'Password reset successfully');
        }

        return $this->error(
            match ($status) {
                Password::INVALID_TOKEN => 'Invalid or expired reset token',
                Password::INVALID_USER  => 'User not found',
                default                 => 'Unable to reset password',
            },
            400
        );
    }
}
