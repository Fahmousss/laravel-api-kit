<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Queries\GetAuthUserId;

use App\Application\Features\Auth\Common\Interfaces\AuthenticatedUserContextInterface;

final class GetAuthUserIdQueryHandler
{
    public function __construct(
        private readonly AuthenticatedUserContextInterface $authenticatedUser
    ) {}

    public function handle(GetAuthUserIdQuery $query): ?int
    {
        return $this->authenticatedUser->currentUserId();
    }
}
