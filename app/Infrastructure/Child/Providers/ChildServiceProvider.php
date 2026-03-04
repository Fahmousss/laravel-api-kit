<?php

declare(strict_types=1);

namespace App\Infrastructure\Child\Providers;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Child\Commands\ImportHistoricalData\ImportHistoricalDataCommand;
use App\Application\Features\Child\Commands\ImportHistoricalData\ImportHistoricalDataCommandHandler;
use App\Application\Features\Child\Commands\LogMeasurement\LogMeasurementCommand;
use App\Application\Features\Child\Commands\LogMeasurement\LogMeasurementCommandHandler;
use App\Application\Features\Child\Commands\RegisterChild\RegisterChildCommand;
use App\Application\Features\Child\Commands\RegisterChild\RegisterChildCommandHandler;
use App\Application\Features\Child\Queries\GetAssignedChildren\GetAssignedChildrenQuery;
use App\Application\Features\Child\Queries\GetAssignedChildren\GetAssignedChildrenQueryHandler;
use App\Domain\Child\Repositories\ChildRepositoryInterface;
use App\Infrastructure\Child\Persistence\EloquentChildRepository;
use Illuminate\Support\ServiceProvider;

final class ChildServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ChildRepositoryInterface::class, EloquentChildRepository::class);

    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(LogMeasurementCommand::class, LogMeasurementCommandHandler::class);
        $commandBus->register(RegisterChildCommand::class, RegisterChildCommandHandler::class);
        $commandBus->register(ImportHistoricalDataCommand::class, ImportHistoricalDataCommandHandler::class);

        $queryBus->register(GetAssignedChildrenQuery::class, GetAssignedChildrenQueryHandler::class);
    }
}
