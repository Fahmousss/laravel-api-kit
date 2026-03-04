<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Infrastructure\Auth\Providers\AuthServiceProvider::class,
    App\Infrastructure\Child\Providers\ChildServiceProvider::class,
    App\Infrastructure\Location\Providers\LocationServiceProvider::class,
];
