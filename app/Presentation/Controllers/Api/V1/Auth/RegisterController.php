<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Application\Auth\Commands\RegisterUserCommand;
use App\Application\Auth\DTOs\UserDTO;
use App\Application\Contracts\CommandBusInterface;
use App\Infrastructure\Auth\Models\User;
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

        $user = User::query()->findOrFail($result->id);

        return $this->created([
            'user'  => new UserResource($user),
            'token' => $result->token,
        ], 'User registered successfully. Please check your email to verify your account.');
    }
}
