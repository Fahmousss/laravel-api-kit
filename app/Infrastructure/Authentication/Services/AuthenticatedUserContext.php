<?php

declare(strict_types=1);

namespace App\Infrastructure\Authentication\Services;

use App\Application\Features\Authentication\Common\Interfaces\AuthenticatedUserContextInterface;
use Illuminate\Http\Request;

final class AuthenticatedUserContext implements AuthenticatedUserContextInterface
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function currentUserId(): ?string
    {
        return $this->request->user()?->id;
    }

    public function currentToken(): ?string
    {
        return $this->request->bearerToken();
    }

    public function isEmailVerified(): bool
    {
        $user = $this->request->user();

        if (! $user) {
            return false;
        }

        return $user->hasVerifiedEmail();
    }
}
