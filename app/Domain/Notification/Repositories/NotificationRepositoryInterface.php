<?php

declare(strict_types=1);

namespace App\Domain\Notification\Repositories;

use App\Domain\Notification\Entities\Notification;
use App\Domain\Shared\Pagination\PaginatedResult;

interface NotificationRepositoryInterface
{
    public function save(Notification $notification): Notification;

    public function markAllReadForUser(string $userId): void;

    public function markAsRead(string $notificationId): void;

    /**
     * @return PaginatedResult<Notification>
     */
    public function paginate(string $userId, bool $unreadOnly, int $perPage, int $page): PaginatedResult;
}
