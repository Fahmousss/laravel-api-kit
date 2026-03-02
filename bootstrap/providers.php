<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Infrastructure\Auth\Providers\AuthServiceProvider::class,
    App\Infrastructure\Product\Providers\ProductServiceProvider::class,
    App\Infrastructure\Book\Providers\BookServiceProvider::class,
];
