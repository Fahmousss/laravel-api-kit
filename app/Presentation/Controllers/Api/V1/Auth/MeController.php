<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\Queries\GetUserById\GetUserByIdQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Resources\UserResource;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MeController extends ApiController
{
    use HasAuthenticatedUser;

    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $userId = $this->getAuthUserId();
        abort_if($userId === null, 401, 'Unauthenticated');

        $userDto = $this->queryBus->dispatch(new GetUserByIdQuery($userId));

        return $this->success(new UserResource($userDto));
    }
}
