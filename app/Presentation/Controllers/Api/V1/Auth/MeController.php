<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MeController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        throw_if($user === null, UserNotFoundException::class, (string) $request->user()?->id);

        return $this->success(new UserResource($user));
    }
}
