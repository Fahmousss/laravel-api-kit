<?php

namespace App\Presentation\Middleware;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Application\Features\Authentication\Queries\GetCurrentUser\GetCurrentUserQuery;
use App\Presentation\Shared\Traits\ApiResponse;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Closure;
use Illuminate\Http\Request;
use App\Infrastructure\Shared\Services\ActorContextResolverService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards /admin/* routes.
 * Aborts 403 if the user is not a system_admin.
 */
class EnsureSystemAdmin
{
    use HasAuthenticatedUser;
    use ApiResponse;
    public function __construct(
        private ActorContextResolverService $resolver,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user  = $this->getCurrentUser();

        if ($user === null) {
            return $this->unauthorized("Unauthorized");
        }

        $actor = $this->resolver->fromUser($user);

        if (! $actor->isSystemAdmin()) {
            return $this->forbidden("System administrator access required.");
        }

        $request->attributes->set('actor', $actor);

        return $next($request);
    }
}
