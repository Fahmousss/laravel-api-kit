<?php

declare(strict_types=1);

namespace App\Infrastructure\Ticket\Services;

use App\Application\Features\Ticket\Common\Interfaces\TicketActivityServiceInterface;
use App\Domain\ActivityLog\Entities\ActivityLog as ActivityLogEntity;
use App\Domain\ActivityLog\Enums\ActivityType;
use App\Domain\ActivityLog\Repositories\ActivityLogRepositoryInterface;

final class TicketActivityService implements TicketActivityServiceInterface
{
    public function __construct(
        private ActivityLogRepositoryInterface $activityLogRepository,
    ) {}

    public function log(string $ticketId, string $actorId, ActivityType $action, array $payload): void
    {
        $log = new ActivityLogEntity(
            id: null,
            ticketId: $ticketId,
            actorId: $actorId,
            action: $action,
            payload: $payload,
            createdAt: null,
        );

        $this->activityLogRepository->save($log);
    }
}
