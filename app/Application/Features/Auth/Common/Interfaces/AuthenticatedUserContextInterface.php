<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Common\Interfaces;

interface AuthenticatedUserContextInterface
{
    /**
     * Get the ID of the currently authenticated user.
     */
    public function currentUserId(): ?string;

    /**
     * Get the bearer token from the current request.
     */
    public function currentToken(): ?string;
}
