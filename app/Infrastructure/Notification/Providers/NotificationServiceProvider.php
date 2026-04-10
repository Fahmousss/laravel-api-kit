<?php

namespace App\Infrastructure\Notification\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Notification\Repositories\NotificationRepositoryInterface;
use App\Infrastructure\Notification\Persistence\EloquentNotificationRepository;
use App\Application\Features\Notification\Common\Interfaces\NotificationServiceInterface;
use App\Infrastructure\Notification\Services\NotificationService;

use App\Application\Features\Notification\Commands\MarkAllRead\MarkAllReadCommand;
use App\Application\Features\Notification\Commands\MarkAllRead\MarkAllReadCommandHandler;
use App\Application\Features\Notification\Queries\ListNotifications\ListNotificationsQuery;
use App\Application\Features\Notification\Queries\ListNotifications\ListNotificationsQueryHandler;
use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;

class NotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationRepositoryInterface::class, EloquentNotificationRepository::class);
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(MarkAllReadCommand::class, MarkAllReadCommandHandler::class);

        $queryBus->register(ListNotificationsQuery::class, ListNotificationsQueryHandler::class);
    }
}
