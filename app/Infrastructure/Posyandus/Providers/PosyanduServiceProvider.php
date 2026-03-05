<?php

declare(strict_types=1);

namespace App\Infrastructure\Posyandus\Providers;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Posyandus\Commands\CreatePosyandu\CreatePosyanduCommand;
use App\Application\Features\Posyandus\Commands\CreatePosyandu\CreatePosyanduCommandHandler;
use App\Application\Features\Posyandus\Queries\GetPosyandus\GetPosyandusQuery;
use App\Application\Features\Posyandus\Queries\GetPosyandus\GetPosyandusQueryHandler;
use App\Domain\Posyandus\Repositories\PosyanduRepositoryInterface;
use App\Infrastructure\Posyandus\Persistence\EloquentPosyanduRepository;
use Illuminate\Support\ServiceProvider;

final class PosyanduServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PosyanduRepositoryInterface::class,
            EloquentPosyanduRepository::class
        );
    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(CreatePosyanduCommand::class, CreatePosyanduCommandHandler::class);
        $queryBus->register(GetPosyandusQuery::class, GetPosyandusQueryHandler::class);
    }
}
