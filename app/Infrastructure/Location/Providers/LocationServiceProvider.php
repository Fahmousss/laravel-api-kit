<?php

declare(strict_types=1);

namespace App\Infrastructure\Location\Providers;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Location\Queries\GetStuntingClusters\GetStuntingClustersQuery;
use App\Application\Features\Location\Queries\GetStuntingClusters\GetStuntingClustersQueryHandler;
use App\Domain\Location\Repositories\PosyanduRepositoryInterface;
use App\Infrastructure\Location\Persistence\EloquentPosyanduRepository;
use Illuminate\Support\ServiceProvider;

final class LocationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PosyanduRepositoryInterface::class, EloquentPosyanduRepository::class);

    }

    public function boot(QueryBusInterface $queryBus): void
    {
        $queryBus->register(GetStuntingClustersQuery::class, GetStuntingClustersQueryHandler::class);
    }
}
