<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Application\Features\Auth\Common\Interfaces\AuthTokenServiceInterface;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Infrastructure\Auth\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final class JwtTokenService implements AuthTokenServiceInterface
{
    public function generateForUser(string $userId): string
    {
        /** @var User $user */
        $user = User::query()->find($userId);

        throw_if($user === null, UserNotFoundException::class, (string) $userId);

        return JWTAuth::fromUser($user);
    }

    public function revokeToken(string $token): void
    {
        JWTAuth::setToken($token)->invalidate();
    }
}
