<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Queries\CheckEmailVerified;

use App\Application\Features\Authentication\Common\Interfaces\AuthenticatedUserContextInterface;

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
