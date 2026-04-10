<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Project\Queries\GetProjectMembers\GetProjectMemberRoleQuery;
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
    use HasAuthenticatedUser;

    public function __construct(
        private QueryBusInterface $queryBus,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $projectId = $request->route('project_id');
        $userId    = $this->getAuthUserId();

        $role = $this->queryBus->dispatch(new GetProjectMemberRoleQuery($projectId, $userId));

        if (! $role) {
            abort(403, 'You are not a member of this project.');
        }

        // Inject role into request for use by controllers/handlers
        $request->merge(['_actor_project_role' => $role->value]);

        return $next($request);
    }
}
