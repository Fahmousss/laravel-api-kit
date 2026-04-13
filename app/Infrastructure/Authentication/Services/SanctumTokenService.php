<?php

declare(strict_types=1);

namespace App\Infrastructure\Authentication\Services;

use App\Application\Features\Authentication\Common\Interfaces\AuthTokenServiceInterface;
use App\Domain\Authentication\Exceptions\UserNotFoundException;
use App\Infrastructure\Authentication\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

final class SanctumTokenService implements AuthTokenServiceInterface
{
    public function generateForUser(string $userId): string
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
