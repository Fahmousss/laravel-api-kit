<?php

declare(strict_types=1);

namespace App\Presentation\Shared\Traits;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Application\Features\Authentication\Queries\CheckEmailVerified\CheckEmailVerifiedQuery;
use App\Application\Features\Authentication\Queries\GetAuthToken\GetAuthTokenQuery;
use App\Application\Features\Authentication\Queries\GetAuthUserId\GetAuthUserIdQuery;
use App\Application\Features\Authentication\Queries\GetCurrentUser\GetCurrentUserQuery;
use App\Application\Features\Authorization\Common\Interfaces\ActorContextResolverServiceInterface;
use App\Domain\Authorization\ValueObjects\ActorContext;
use Illuminate\Http\Request;

trait HasAuthenticatedUser
{

    /**
     * Retrieve the currently authenticated user's ID via the Query Bus.
     */
    private function getAuthUserId(): ?string
    {
        return app(QueryBusInterface::class)->dispatch(new GetAuthUserIdQuery());
    }

    /**
     * Retrieve the current bearer token via the Query Bus.
     */
    private function getAuthToken(): ?string
    {
        return app(QueryBusInterface::class)->dispatch(new GetAuthTokenQuery());
    }

    /**
     * Check if the currently authenticated user has verified their email via the Query Bus.
     */
    private function isAuthEmailVerified(): bool
    {
        return app(QueryBusInterface::class)->dispatch(new CheckEmailVerifiedQuery());
    }

    private function getCurrentUser(): ?UserDTO
    {
        return app(QueryBusInterface::class)->dispatch(new GetCurrentUserQuery());
    }

    /**
     * Returns the typed ActorContext set by middleware.
     * For project-scoped routes, always use this over userId().
     */
    protected function actor(Request $request): ActorContext
    {
        return $request->attributes->get('actor');
    }

    /**
     * Returns ActorContext for non-project-scoped routes (no project role).
     * Constructs it fresh from the authenticated user.
     */
    protected function actorFromRequest(): ActorContext
    {
        $user = $this->getCurrentUser();

        return app(ActorContextResolverServiceInterface::class)->fromUser($user);
    }
}
