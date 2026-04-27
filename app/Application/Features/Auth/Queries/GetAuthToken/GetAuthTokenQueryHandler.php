<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Queries\GetAuthToken;

use App\Application\Features\Auth\Common\Interfaces\AuthenticatedUserContextInterface;

final readonly class GetAuthTokenQueryHandler
{
    public function __construct(
        private AuthenticatedUserContextInterface $authenticatedUser
    ) {}

    public function handle(): ?string
    {
        return $this->authenticatedUser->currentToken();
    }
}
