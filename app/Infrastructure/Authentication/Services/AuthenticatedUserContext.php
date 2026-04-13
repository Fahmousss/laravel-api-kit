<?php

declare(strict_types=1);

namespace App\Infrastructure\Authentication\Services;

use App\Application\Features\Authentication\Common\Interfaces\AuthenticatedUserContextInterface;
use App\Domain\Authentication\Entities\UserEntity;
use App\Infrastructure\Shared\Traits\EntityMapper;
use Illuminate\Http\Request;

final class AuthenticatedUserContext implements AuthenticatedUserContextInterface
{
    use EntityMapper;

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

    public function currentUser(): ?UserEntity
    {
        $user = $this->request->user();

        if (! $user) {
            return null;
        }

        return $this->mapToEntity($user, UserEntity::class);
    }
}

