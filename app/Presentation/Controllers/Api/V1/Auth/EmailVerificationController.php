<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\Commands\ResendVerificationEmail\ResendVerificationEmailCommand;
use App\Application\Features\Auth\Commands\VerifyEmail\VerifyEmailCommand;
use App\Application\Features\Auth\Queries\GetUserById\GetUserByIdQuery;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\ResendVerificationRequest;
use App\Presentation\Requests\Api\V1\VerifyEmailRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

final class EmailVerificationController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus
    ) {}

    public function verify(VerifyEmailRequest $request): JsonResponse
    {
        $userId = $request->user()?->id;
        throw_if($userId === null, UserNotFoundException::class);

        $userDto = $this->queryBus->dispatch(new GetUserByIdQuery($userId));

        throw_if($userDto === null, UserNotFoundException::class, (string) $userId);

        if ($userDto->emailVerifiedAt !== null) {
            return $this->success(message: 'Email already verified');
        }

        $this->commandBus->dispatch(new VerifyEmailCommand($userId));

        return $this->success(message: 'Email verified successfully');
    }

    public function resend(ResendVerificationRequest $request): JsonResponse
    {
        try {
            $this->commandBus->dispatch(new ResendVerificationEmailCommand($request->email));
        } catch (UserNotFoundException) {
            return $this->notFound(message: 'User not found');
        } catch (InvalidArgumentException) {
            return $this->error(message: 'Email already verified');
        }

        return $this->success(message: 'Verification email sent successfully');
    }
}
