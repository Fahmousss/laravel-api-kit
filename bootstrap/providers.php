<?php

declare(strict_types=1);

return [
    App\Infrastructure\Auth\Providers\AuthServiceProvider::class,
    App\Infrastructure\Test\Providers\TestServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthorizationServiceProvider::class,
];
