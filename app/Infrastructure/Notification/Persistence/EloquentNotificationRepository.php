<?php

namespace App\Infrastructure\Notification\Persistence;

use App\Domain\Notification\Entities\Notification as NotificationEntity;
use App\Domain\Notification\Repositories\NotificationRepositoryInterface;
use App\Domain\Notification\Enums\NotificationType;
use App\Domain\Shared\Pagination\PaginatedResult;
use App\Infrastructure\Notification\Models\Notification as NotificationModel;
use App\Infrastructure\Shared\Traits\EntityMapper;

class EloquentNotificationRepository implements NotificationRepositoryInterface
{
    use EntityMapper;

    public function save(NotificationEntity $notification): NotificationEntity
    {
        $model = new NotificationModel();
        $model->fill([
            'user_id'    => $notification->userId,
            'ticket_id'  => $notification->ticketId,
            'type'       => $notification->type->value,
            'payload'    => $notification->payload,
            'read'       => $notification->read,
            'created_at' => now(),
        ])->save();

        return $this->mapToEntity($model->fresh(), NotificationEntity::class);
    }

    public function markAllReadForUser(string $userId): void
    {
        NotificationModel::where('user_id', $userId)
                          ->where('read', false)
                          ->update(['read' => true]);
    }

    public function markAsRead(string $notificationId): void
    {
        NotificationModel::where('id', $notificationId)->update(['read' => true]);
    }

    public function paginate(string $userId, bool $unreadOnly, int $perPage, int $page): PaginatedResult
    {
        $query = NotificationModel::where('user_id', $userId);

        if ($unreadOnly) {
            $query->where('read', false);
        }

        $paginator = $query->orderByDesc('created_at')->paginate($perPage, ['*'], 'page', $page);

        return new PaginatedResult(
            items:       array_map(fn(NotificationModel $m) => $this->mapToEntity($m, NotificationEntity::class), $paginator->items()),
            total:       $paginator->total(),
            perPage:     $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage:    $paginator->lastPage(),
        );
    }
}
