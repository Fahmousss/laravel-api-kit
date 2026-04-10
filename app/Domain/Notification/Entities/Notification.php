<?php

declare(strict_types=1);

namespace App\Domain\Notification\Entities;

use App\Domain\Notification\Enums\NotificationType;

final class Notification
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $userId,
        public readonly ?string $ticketId,
        public readonly NotificationType $type,
        public readonly array $payload,
        public bool $read,
        public readonly ?string $createdAt,
    ) {}

    public function markAsRead(): void
    {
        $this->read = true;
    }
}
