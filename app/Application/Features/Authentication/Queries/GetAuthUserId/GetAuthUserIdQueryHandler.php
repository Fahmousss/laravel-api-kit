<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Queries\GetAuthUserId;

use App\Application\Features\Authentication\Common\Interfaces\AuthenticatedUserContextInterface;

final class GetAuthUserIdQueryHandler
{
    public function __construct(
        private readonly AuthenticatedUserContextInterface $authenticatedUser
    ) {}

    public function handle(GetAuthUserIdQuery $query): ?string
    {
        return $this->authenticatedUser->currentUserId();
    }
}
