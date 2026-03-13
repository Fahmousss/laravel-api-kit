<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Common\Interfaces;

interface SessionServiceInterface
{
    /**
     * Check if the user is authenticated.
     */
    public function check(): bool;

    /**
     * Get the ID of the authenticated user.
     */
    public function id(): ?int;

    /**
     * Log a user into the application by their ID.
     */
    public function loginById(int $id, bool $remember = false): void;

    /**
     * Log the user out of the application.
     */
    public function logout(): void;

    /**
     * Invalidate and regenerate the session (usually after login/logout).
     */
    public function refresh(): void;

    /**
     * Regenerate the session ID without losing data (usually after login).
     */
    public function regenerate(): void;
}
