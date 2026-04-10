<?php

namespace App\Application\Features\Notification\Queries\ListNotifications;

readonly class ListNotificationsQuery
{
    public function __construct(
        public string $userId,
        public bool   $unreadOnly = false,
        public int    $perPage    = 20,
        public int    $page       = 1,
    ) {}
}
