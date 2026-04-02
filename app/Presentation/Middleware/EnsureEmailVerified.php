<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Presentation\Shared\Traits\AuthContextTrait;
use Closure;
use Illuminate\Http\Request;

final class EnsureEmailVerified
{
    use AuthContextTrait;

    /**
     * Ensure the user's email is verified before allowing access.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->getAuthUserId() === null) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        if (! $this->isAuthEmailVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Your email address is not verified. Please verify your email to continue.',
            ], 403);
        }

        return $next($request);
    }
}
