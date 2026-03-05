<?php

declare(strict_types=1);

namespace App\Infrastructure\Dashboard\Providers;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary\GetGeoTaggedStuntingSummaryQuery;
use App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary\GetGeoTaggedStuntingSummaryQueryHandler;
use Illuminate\Support\ServiceProvider;

final class DashboardServiceProviders extends ServiceProvider
{
    public function boot(QueryBusInterface $queryBus): void
    {
        $queryBus->register(GetGeoTaggedStuntingSummaryQuery::class, GetGeoTaggedStuntingSummaryQueryHandler::class);
    }
}
