<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Authentication\Commands\RegisterUser\RegisterUserCommand;
use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\RegisterRequest;
use App\Presentation\Resources\UserResource;
use Illuminate\Http\JsonResponse;

final class RegisterController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        /** @var UserDTO $result */
        $result = $this->commandBus->dispatch(new RegisterUserCommand(
            name: $request->name,
            email: $request->email,
            password: $request->password,
        ));

        return $this->created([
            'user'  => new UserResource($result),
            'token' => $result->token,
        ], 'User registered successfully. Please check your email to verify your account.');
    }
}
