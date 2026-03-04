<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Application\Features\Auth\Common\Interfaces\AuthTokenServiceInterface;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Infrastructure\Auth\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

final class SanctumTokenService implements AuthTokenServiceInterface
{
    public function generateForUser(int $userId): string
    {
        /** @var User $user */
        $user = User::query()->find($userId);

        throw_if($user === null, UserNotFoundException::class, (string) $userId);

        return $user->createToken('auth-token')->plainTextToken;
    }

    public function revokeToken(string $token): void
    {
        $accessToken = PersonalAccessToken::findToken($token);
        $accessToken?->delete();
    }
}
