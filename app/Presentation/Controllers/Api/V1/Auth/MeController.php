<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\Queries\GetUserById\GetUserByIdQuery;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MeController extends ApiController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $userId = $request->user()?->id;
        throw_if($userId === null, UserNotFoundException::class);

        $userDto = $this->queryBus->dispatch(new GetUserByIdQuery($userId));
        throw_if($userDto === null, UserNotFoundException::class, (string) $userId);

        return $this->success(new UserResource($userDto));
    }
}
