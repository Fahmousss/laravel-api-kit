<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        abort_unless($request->user(), 403, 'Unauthorized');

        // The exact Enum string values are saved in the role_user table.
        $userRoles = $request->user()->roleModels->pluck('role')->all();

        foreach ($roles as $role) {
            if (in_array($role, $userRoles, true)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}
