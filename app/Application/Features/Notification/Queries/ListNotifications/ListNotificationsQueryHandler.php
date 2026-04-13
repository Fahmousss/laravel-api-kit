<?php

declare(strict_types=1);

namespace App\Application\Features\Notification\Queries\ListNotifications;

use App\Application\Features\Notification\DTOs\NotificationDTO;
use App\Domain\Notification\Entities\Notification;
use App\Domain\Notification\Repositories\NotificationRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

final class ListNotificationsQueryHandler
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository,
    ) {}

    public function handle(ListNotificationsQuery $query): PaginatedResult
    {
        $result = $this->notificationRepository->paginate(
            $query->userId,
            $query->unreadOnly,
            $query->perPage,
            $query->page,
        );

        return new PaginatedResult(
            items: array_map(
                fn (Notification $n): NotificationDTO => NotificationDTO::fromEntity($n),
                $result->items,
            ),
            total: $result->total,
            perPage: $result->perPage,
            currentPage: $result->currentPage,
            lastPage: $result->lastPage,
        );
    }
}

