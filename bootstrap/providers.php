<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Infrastructure\Auth\Providers\AuthServiceProvider::class,
    App\Infrastructure\Test\Providers\TestServiceProvider::class,
];
