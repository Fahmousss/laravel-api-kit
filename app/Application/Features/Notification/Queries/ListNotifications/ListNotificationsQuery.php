<?php

declare(strict_types=1);

namespace App\Application\Features\Notification\Queries\ListNotifications;

final readonly class ListNotificationsQuery
{
    public function __construct(
        public string $userId,
        public bool $unreadOnly = false,
        public int $perPage = 20,
        public int $page = 1,
    ) {}
}
