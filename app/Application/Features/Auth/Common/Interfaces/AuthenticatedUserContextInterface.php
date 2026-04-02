<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Common\Interfaces;

interface AuthenticatedUserContextInterface
{
    /**
     * Get the ID of the currently authenticated user.
     */
    public function currentUserId(): ?int;

    /**
     * Get the bearer token from the current request.
     */
    public function currentToken(): ?string;

    /**
     * Check if the currently authenticated user has verified their email.
     */
    public function isEmailVerified(): bool;
}
