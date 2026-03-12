<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Infrastructure\Auth\Providers\AuthenticationServiceProvider::class,
    App\Infrastructure\Shared\Providers\RateLimitServiceProvider::class,
    App\Infrastructure\Shared\Providers\DocumentationServiceProvider::class,
];
