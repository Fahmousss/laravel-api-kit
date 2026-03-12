<?php

declare(strict_types=1);

use App\Presentation\Middleware\Authenticate;
use App\Presentation\Middleware\EnsureEmailVerified;
use App\Presentation\Middleware\ForceJsonResponse;
use App\Presentation\Middleware\LogApiRequests;
use App\Presentation\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        // commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'force.json' => ForceJsonResponse::class,
            'log.api'    => LogApiRequests::class,
            'verified'   => EnsureEmailVerified::class,
            'auth'       => Authenticate::class,
            'role'       => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, Throwable $e): bool => $request->is('api/*') || $request->expectsJson()
        );
    })->create();
