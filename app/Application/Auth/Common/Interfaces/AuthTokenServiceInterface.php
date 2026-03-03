<?php

declare(strict_types=1);

namespace App\Application\Auth\Common\Interfaces;

interface AuthTokenServiceInterface
{
    /**
     * Generate an authentication token for the given user ID.
     */
    public function generateForUser(int $userId): string;
}
