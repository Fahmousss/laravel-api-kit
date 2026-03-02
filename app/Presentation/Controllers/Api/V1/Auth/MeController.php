<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MeController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return $this->success(new UserResource($request->user()));
    }
}
