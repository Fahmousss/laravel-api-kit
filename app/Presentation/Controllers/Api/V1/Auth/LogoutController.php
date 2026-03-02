<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Infrastructure\Auth\Models\User;
use App\Presentation\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LogoutController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return $this->success(message: 'Logged out successfully');
    }
}
