<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Authentication\Commands\ResendVerificationEmail\ResendVerificationEmailCommand;
use App\Application\Features\Authentication\Commands\VerifyEmail\VerifyEmailCommand;
use App\Application\Features\Authentication\Queries\GetUserById\GetUserByIdQuery;
use App\Domain\Authentication\Exceptions\UserNotFoundException;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Authentication\ResendVerificationRequest;
use App\Presentation\Requests\Api\V1\Authentication\VerifyEmailRequest;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

final class EmailVerificationController extends ApiController
{
    use HasAuthenticatedUser;

    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus
    ) {}

    public function verify(VerifyEmailRequest $request): JsonResponse
    {
        $userId = $this->getAuthUserId();
        abort_if($userId === null, 401, 'Unauthenticated');

        $userDto = $this->queryBus->dispatch(new GetUserByIdQuery($userId));

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
