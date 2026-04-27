<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Common\Interfaces;

interface AuthTokenServiceInterface
{
    /**
     * Generate an authentication token for the given user ID.
     */
    public function generateForUser(string $userId): string;

    /**
     * Revoke the given bearer token.
     */
    public function revokeToken(string $token): void;
}
