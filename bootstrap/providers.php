<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Infrastructure\Auth\Providers\AuthenticationServiceProvider::class,
    App\Infrastructure\Auth\Providers\AuthorizationServiceProvider::class,
    App\Infrastructure\Posyandus\Providers\PosyanduServiceProvider::class,
    App\Infrastructure\Children\Providers\ChildrenServiceProvider::class,
    App\Infrastructure\Measurements\Providers\MeasurementServiceProvider::class,
    App\Infrastructure\Dashboard\Providers\DashboardServiceProviders::class,
];
