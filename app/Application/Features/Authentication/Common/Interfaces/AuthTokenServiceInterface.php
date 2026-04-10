<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Common\Interfaces;

interface AuthTokenServiceInterface
{
    /**
     * Generate an authentication token for the given user ID.
     */
    public function generateForUser(int $userId): string;

    /**
     * Revoke the given bearer token.
     */
    public function revokeToken(string $token): void;
}
