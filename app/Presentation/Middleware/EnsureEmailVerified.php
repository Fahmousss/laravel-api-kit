<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Presentation\Shared\Traits\ApiResponse;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Closure;
use Illuminate\Http\Request;

final class EnsureEmailVerified
{
    use ApiResponse;
    use HasAuthenticatedUser;

    /**
     * Ensure the user's email is verified before allowing access.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->getAuthUserId() === null) {
            return $this->unauthorized();
        }

        if (! $this->isAuthEmailVerified()) {
            return $this->forbidden('Your email address is not verified. Please verify your email to continue.');
        }

        return $next($request);
    }
}
