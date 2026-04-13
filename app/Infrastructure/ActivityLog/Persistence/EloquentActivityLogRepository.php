<?php

declare(strict_types=1);

namespace App\Infrastructure\ActivityLog\Persistence;

use App\Domain\ActivityLog\Entities\ActivityLog as ActivityLogEntity;
use App\Domain\ActivityLog\Repositories\ActivityLogRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;
use App\Infrastructure\ActivityLog\Models\ActivityLog as ActivityLogModel;
use App\Infrastructure\Shared\Traits\EntityMapper;

final class EloquentActivityLogRepository implements ActivityLogRepositoryInterface
{
    use EntityMapper;

    public function save(ActivityLogEntity $log): ActivityLogEntity
    {
        $model = new ActivityLogModel();
        $model->fill([
            'ticket_id'  => $log->ticketId,
            'actor_id'   => $log->actorId,
            'action'     => $log->action->value,
            'payload'    => $log->payload,
            'created_at' => $log->createdAt ?? now(),
        ])->save();

        return $this->mapToEntity($model->fresh(), ActivityLogEntity::class);
    }

    public function paginate(string $ticketId, int $perPage, int $page): PaginatedResult
    {
        $paginator = ActivityLogModel::where('ticket_id', $ticketId)
            ->orderBy('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return new PaginatedResult(
            items: array_map(
                fn (ActivityLogModel $m) => $this->mapToEntity($m, ActivityLogEntity::class),
                $paginator->items(),
            ),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage(),
        );
    }
}
