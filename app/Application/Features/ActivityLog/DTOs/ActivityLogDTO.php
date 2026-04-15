<?php

declare(strict_types=1);

namespace App\Application\Features\ActivityLog\DTOs;

use App\Domain\ActivityLog\Entities\ActivityLog;

final readonly class ActivityLogDTO
{
    public function __construct(
        public string $id,
        public string $ticketId,
        public string $actorId,
        public string $action,
        public array $payload,
        public string $createdAt,
    ) {}

    public static function fromEntity(ActivityLog $log): self
    {
        return new self(
            id: $log->id,
            ticketId: $log->ticketId,
            actorId: $log->actorId,
            action: $log->action->value,
            payload: $log->payload,
            createdAt: $log->createdAt ?? now()->toIso8601String(),
        );
    }
}
