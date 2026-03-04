<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Auth\Commands\ResetPassword\ResetPasswordCommand;
use App\Application\Features\Auth\Commands\SendPasswordResetLink\SendPasswordResetLinkCommand;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\ForgotPasswordRequest;
use App\Presentation\Requests\Api\V1\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

final class PasswordResetController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $status = $this->commandBus->dispatch(new SendPasswordResetLinkCommand($request->email));

        if ($status === Password::RESET_LINK_SENT) {
            return $this->success(message: 'Password reset link sent to your email');
        }

        return $this->error('Unable to send reset link', 500);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $status = $this->commandBus->dispatch(new ResetPasswordCommand(
            $request->email,
            $request->password,
            $request->password_confirmation,
            $request->token
        ));

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
