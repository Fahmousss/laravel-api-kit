<?php

declare(strict_types=1);

namespace App\Providers;

use App\Application\Bus\CommandBus;
use App\Application\Bus\QueryBus;
use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        CommandBusInterface::class => CommandBus::class,
        QueryBusInterface::class   => QueryBus::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void {}
}
