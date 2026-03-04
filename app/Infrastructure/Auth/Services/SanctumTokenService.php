<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Application\Features\Auth\Common\Interfaces\AuthTokenServiceInterface;
use App\Infrastructure\Auth\Models\User;

final class SanctumTokenService implements AuthTokenServiceInterface
{
    public function generateForUser(int $userId): string
    {
        /** @var User $user */
        $user = User::query()->findOrFail($userId);

        return $user->createToken('auth-token')->plainTextToken;
    }
}
