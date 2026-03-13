<?php

declare(strict_types=1);

namespace App\Presentation\Shared\Traits;

use App\Application\Features\Auth\DTOs\UserDTO;
use App\Application\Features\Auth\Queries\GetAuthenticatedUser\GetAuthenticatedUserQuery;

trait HasAuthenticatedUser
{
    private function getAuthenticatedUser(): ?UserDTO
    {
        return $this->queryBus->dispatch(new GetAuthenticatedUserQuery());
    }
}
