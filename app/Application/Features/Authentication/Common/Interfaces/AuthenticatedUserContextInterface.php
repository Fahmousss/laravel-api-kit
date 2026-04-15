<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Common\Interfaces;

use App\Domain\Authentication\Entities\UserEntity;

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

    /**
     * Check if the currently authenticated user has verified their email.
     */
    public function isEmailVerified(): bool;

    /**
     * Get the currently authenticated user as a Domain entity.
     */
    public function currentUser(): ?UserEntity;
}
