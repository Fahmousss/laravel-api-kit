<?php

namespace App\Application\Features\Notification\Queries\ListNotifications;

use App\Domain\Notification\Repositories\NotificationRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

class ListNotificationsQueryHandler
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository,
    ) {}

    public function handle(ListNotificationsQuery $query): PaginatedResult
    {
        return $this->notificationRepository->paginate(
            $query->userId,
            $query->unreadOnly,
            $query->perPage,
            $query->page,
        );
    }
}
