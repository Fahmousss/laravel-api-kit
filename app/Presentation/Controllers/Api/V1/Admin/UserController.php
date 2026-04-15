<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Admin;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Authentication\Commands\CreateUser\CreateUserCommand;
use App\Application\Features\Authentication\Commands\DeleteUser\DeleteUserCommand;
use App\Application\Features\Authentication\Commands\UpdateUser\UpdateUserCommand;
use App\Application\Features\Authentication\Queries\GetAllUsers\GetAllUsersQuery;
use App\Application\Features\Authentication\Queries\GetUserById\GetUserByIdQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Admin\CreateUserRequest;
use App\Presentation\Requests\Api\V1\Admin\UpdateUserRequest;
use App\Presentation\Resources\Authentication\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserController extends ApiController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->queryBus->dispatch(new GetAllUsersQuery(
            page: (int) $request->input('page', 1),
            perPage: (int) $request->input('per_page', 15),
        ));

        return $this->paginated(
            paginatedResult: $result,
            data: UserResource::collection($result->items),
        );
    }

    public function show(string $user_id): JsonResponse
    {
        $dto = $this->queryBus->dispatch(new GetUserByIdQuery($user_id));

        return $this->success(data: new UserResource($dto));
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $dto = $this->commandBus->dispatch(new CreateUserCommand(
            name: (string) $request->input('name'),
            email: (string) $request->input('email'),
            password: (string) $request->input('password'),
        ));

        return $this->created(data: new UserResource($dto));
    }

    public function update(UpdateUserRequest $request, string $user_id): JsonResponse
    {
        $dto = $this->commandBus->dispatch(new UpdateUserCommand(
            id: $user_id,
            name: $request->has('name') ? (string) $request->input('name') : null,
            email: $request->has('email') ? (string) $request->input('email') : null,
            password: $request->has('password') ? (string) $request->input('password') : null,
        ));

        return $this->success(data: new UserResource($dto));
    }

    public function destroy(string $user_id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteUserCommand($user_id));

        return $this->noContent();
    }
}
