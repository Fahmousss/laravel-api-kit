<?php

declare(strict_types=1);

namespace App\Infrastructure\ActivityLog\Providers;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\ActivityLog\Queries\ListActivityLogs\ListActivityLogsQuery;
use App\Application\Features\ActivityLog\Queries\ListActivityLogs\ListActivityLogsQueryHandler;
use App\Domain\ActivityLog\Repositories\ActivityLogRepositoryInterface;
use App\Infrastructure\ActivityLog\Persistence\EloquentActivityLogRepository;
use Illuminate\Support\ServiceProvider;

final class ActivityLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ActivityLogRepositoryInterface::class, EloquentActivityLogRepository::class);
    }

    public function boot(QueryBusInterface $queryBus): void
    {
        $queryBus->register(ListActivityLogsQuery::class, ListActivityLogsQueryHandler::class);
    }
}
