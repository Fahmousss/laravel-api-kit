<?php

declare(strict_types=1);

namespace App\Presentation\Shared\Traits;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\Queries\GetAuthToken\GetAuthTokenQuery;
use App\Application\Features\Auth\Queries\GetAuthUserId\GetAuthUserIdQuery;

trait HasAuthenticatedUser
{
    /**
     * Retrieve the currently authenticated user's ID via the Query Bus.
     */
    private function getAuthUserId(): ?string
    {
        return resolve(QueryBusInterface::class)->dispatch(new GetAuthUserIdQuery());
    }

    /**
     * Retrieve the current bearer token via the Query Bus.
     */
    private function getAuthToken(): ?string
    {
        return resolve(QueryBusInterface::class)->dispatch(new GetAuthTokenQuery());
    }
}
