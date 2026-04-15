<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Domain\Authorization\Enums\SystemRole;
use App\Infrastructure\Authorization\Services\ActorContextResolverService;
use App\Presentation\Shared\Traits\ApiResponse;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Injects the actor's project role into the request.
 * Aborts with 403 if the user is not a member of the project.
 * Route must have {project_id} param.
 */
final class EnsureProjectMember
{
    use ApiResponse;
    use HasAuthenticatedUser;

    public function __construct(
        private ActorContextResolverService $resolver
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $projectId = $request->route('project_id');
        $user      = $this->getCurrentUser();

        if ($user === null) {
            return $this->unauthorized('Unauthorized');
        }

        $actor = $this->resolver->fromUserAndProject($user, $projectId);

        if ($actor === null) {
            return $this->forbidden('You are not member of this project');
        }

        $request->attributes->set('actor', $actor);

        return $next($request);
    }
}
