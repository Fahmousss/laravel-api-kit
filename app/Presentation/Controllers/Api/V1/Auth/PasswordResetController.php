<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Authentication\Commands\ResetPassword\ResetPasswordCommand;
use App\Application\Features\Authentication\Commands\SendPasswordResetLink\SendPasswordResetLinkCommand;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Authentication\ForgotPasswordRequest;
use App\Presentation\Requests\Api\V1\Authentication\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;

final class PasswordResetController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        /** @var \App\Application\Features\Authentication\DTOs\PasswordResetStatusDTO $result */
        $result = $this->commandBus->dispatch(new SendPasswordResetLinkCommand($request->email));

        return $result->success
            ? $this->success(message: $result->message)
            : $this->error($result->message, 500);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        /** @var \App\Application\Features\Authentication\DTOs\PasswordResetStatusDTO $result */
        $result = $this->commandBus->dispatch(new ResetPasswordCommand(
            $request->email,
            $request->password,
            $request->password_confirmation,
            $request->token
        ));

        return $result->success
            ? $this->success(message: $result->message)
            : $this->error($result->message, 400);
    }
}

