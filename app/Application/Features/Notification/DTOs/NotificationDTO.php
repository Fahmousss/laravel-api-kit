<?php

declare(strict_types=1);

namespace App\Application\Features\Notification\DTOs;

use App\Domain\Notification\Entities\Notification;
use App\Domain\Notification\Enums\NotificationType;

final readonly class NotificationDTO
{
    public function __construct(
        public string $id,
        public string $userId,
        public ?string $ticketId,
        public NotificationType $type,
        public array $payload,
        public bool $read,
        public string $createdAt,
    ) {}

    public static function fromEntity(Notification $notification): self
    {
        return new self(
            id: $notification->id,
            userId: $notification->userId,
            ticketId: $notification->ticketId,
            type: $notification->type,
            payload: $notification->payload,
            read: $notification->read,
            createdAt: $notification->createdAt ?? now()->toIso8601String(),
        );
    }
}
