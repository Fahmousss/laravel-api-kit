<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Queries\GetAuthToken;

use App\Application\Features\Authentication\Common\Interfaces\AuthenticatedUserContextInterface;

final class GetAuthTokenQueryHandler
{
    public function __construct(
        private readonly AuthenticatedUserContextInterface $authenticatedUser
    ) {}

    public function handle(GetAuthTokenQuery $query): ?string
    {
        return $this->authenticatedUser->currentToken();
    }
}
