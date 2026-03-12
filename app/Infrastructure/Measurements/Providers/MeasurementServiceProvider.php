<?php

declare(strict_types=1);

namespace App\Infrastructure\Measurements\Providers;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Measurements\Commands\CreateMeasurement\CreateMeasurementCommand;
use App\Application\Features\Measurements\Commands\CreateMeasurement\CreateMeasurementCommandHandler;
use App\Application\Features\Measurements\Queries\GetAllMeasurements\GetAllMeasurementsQuery;
use App\Application\Features\Measurements\Queries\GetAllMeasurements\GetAllMeasurementsQueryHandler;
use App\Domain\Measurements\Repositories\MeasurementRepositoryInterface;
use App\Domain\Measurements\Services\StuntingCalculatorService;
use App\Domain\Measurements\Services\StuntingCalculatorServiceInterface;
use App\Infrastructure\Measurements\Persistence\EloquentMeasurementRepository;
use Illuminate\Support\ServiceProvider;

final class MeasurementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MeasurementRepositoryInterface::class, EloquentMeasurementRepository::class);
        $this->app->bind(StuntingCalculatorServiceInterface::class, StuntingCalculatorService::class);
    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $queryBus->register(GetAllMeasurementsQuery::class, GetAllMeasurementsQueryHandler::class);

        $commandBus->register(CreateMeasurementCommand::class, CreateMeasurementCommandHandler::class);
    }
}
