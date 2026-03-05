<?php

namespace App\Infrastructure\Dashboard\Providers;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary\GetGeoTaggedStuntingSummaryQuery;
use App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary\GetGeoTaggedStuntingSummaryQueryHandler;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProviders extends ServiceProvider
{
    public function boot(QueryBusInterface $queryBus): void
    {
        $queryBus->register(GetGeoTaggedStuntingSummaryQuery::class, GetGeoTaggedStuntingSummaryQueryHandler::class);
    }
}