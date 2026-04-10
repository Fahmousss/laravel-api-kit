<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Authentication\Commands\LogoutUser\LogoutUserCommand;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LogoutController extends ApiController
{
    use HasAuthenticatedUser;

    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $token = $this->getAuthToken();
        if ($token !== null) {
            $this->commandBus->dispatch(new LogoutUserCommand($token));
        }

        return $this->success(message: 'Logged out successfully');
    }
}
