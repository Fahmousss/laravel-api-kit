<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Infrastructure\Authorization\Services\ActorContextResolverService;
use App\Presentation\Shared\Traits\ApiResponse;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards /admin/* routes.
 * Aborts 403 if the user is not a system_admin.
 */
final class EnsureSystemAdmin
{
    use ApiResponse;
    use HasAuthenticatedUser;

    public function __construct(
        private ActorContextResolverService $resolver,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $this->getCurrentUser();

        if ($user === null) {
            return $this->unauthorized('Unauthorized');
        }

        $actor = $this->resolver->fromUser($user);

        if (! $actor->isSystemAdmin()) {
            return $this->forbidden('System administrator access required.');
        }

        $request->attributes->set('actor', $actor);

        return $next($request);
    }
}
