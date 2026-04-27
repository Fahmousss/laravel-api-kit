<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Application\Features\Auth\Common\Interfaces\AuthenticatedUserContextInterface;
use Illuminate\Http\Request;

final readonly class AuthenticatedUserContext implements AuthenticatedUserContextInterface
{
    public function __construct(
        private Request $request
    ) {}

    public function currentUserId(): ?string
    {
        return $this->request->user()?->id;
    }

    public function currentToken(): ?string
    {
        return $this->request->bearerToken();
    }
}
