<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

final class Authenticate extends \Illuminate\Auth\Middleware\Authenticate
{
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson() && ! $request->is('api/*')) {
            return route('web.login');
        }

        return null;
    }

    protected function unauthenticated($request, array $guards): void
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->redirectTo($request)
        );
    }
}
