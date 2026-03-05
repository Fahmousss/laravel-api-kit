<?php

namespace App\Infrastructure\Posyandus\Providers;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use Illuminate\Support\ServiceProvider;

final class PosyanduServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Posyandus\Repositories\PosyanduRepositoryInterface::class,
            \App\Infrastructure\Posyandus\Persistence\EloquentPosyanduRepository::class
        );
    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(\App\Application\Features\Posyandus\Commands\CreatePosyandu\CreatePosyanduCommand::class, \App\Application\Features\Posyandus\Commands\CreatePosyandu\CreatePosyanduCommandHandler::class);
        $queryBus->register(\App\Application\Features\Posyandus\Queries\GetPosyandus\GetPosyandusQuery::class, \App\Application\Features\Posyandus\Queries\GetPosyandus\GetPosyandusQueryHandler::class);
    }
}

