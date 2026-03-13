<?php

declare(strict_types=1);

return [
    App\Infrastructure\Auth\Providers\AuthenticationServiceProvider::class,
    App\Infrastructure\Web\Providers\RateLimiterServiceProvider::class,
    App\Infrastructure\Web\Providers\ViewComposerServiceProvider::class,
    App\Providers\AppServiceProvider::class,
];
