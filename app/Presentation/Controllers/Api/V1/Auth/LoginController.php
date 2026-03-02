<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\DTOs\UserDTO;
use App\Application\Features\Auth\Queries\LoginUser\LoginUserQuery;
use App\Infrastructure\Auth\Models\User;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\LoginRequest;
use App\Presentation\Resources\UserResource;
use Illuminate\Http\JsonResponse;

final class LoginController extends ApiController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        /** @var null|UserDTO $result */
        $result = $this->queryBus->dispatch(new LoginUserQuery(
            email: $request->email,
            password: $request->password,
        ));

        if ($result === null) {
            return $this->unauthorized('Invalid credentials');
        }

        $user = User::query()->findOrFail($result->id);

        return $this->success([
            'user'  => new UserResource($user),
            'token' => $result->token,
        ], 'Login successful');
    }
}
