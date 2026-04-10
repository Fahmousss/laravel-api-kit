<?php

declare(strict_types=1);

namespace App\Presentation\Shared\Traits;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Authentication\Queries\CheckEmailVerified\CheckEmailVerifiedQuery;
use App\Application\Features\Authentication\Queries\GetAuthToken\GetAuthTokenQuery;
use App\Application\Features\Authentication\Queries\GetAuthUserId\GetAuthUserIdQuery;

trait HasAuthenticatedUser
{
    /**
     * Retrieve the currently authenticated user's ID via the Query Bus.
     */
    private function getAuthUserId(): ?string
    {
        return app(QueryBusInterface::class)->dispatch(new GetAuthUserIdQuery());
    }

    /**
     * Retrieve the current bearer token via the Query Bus.
     */
    private function getAuthToken(): ?string
    {
        return app(QueryBusInterface::class)->dispatch(new GetAuthTokenQuery());
    }

    /**
     * Check if the currently authenticated user has verified their email via the Query Bus.
     */
    private function isAuthEmailVerified(): bool
    {
        return app(QueryBusInterface::class)->dispatch(new CheckEmailVerifiedQuery());
    }
}
