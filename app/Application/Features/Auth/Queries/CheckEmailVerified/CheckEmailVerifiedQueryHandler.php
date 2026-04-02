<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Queries\CheckEmailVerified;

use App\Application\Features\Auth\Common\Interfaces\AuthenticatedUserContextInterface;

final class CheckEmailVerifiedQueryHandler
{
    public function __construct(
        private readonly AuthenticatedUserContextInterface $authenticatedUser
    ) {}

    public function handle(CheckEmailVerifiedQuery $query): bool
    {
        return $this->authenticatedUser->isEmailVerified();
    }
}
