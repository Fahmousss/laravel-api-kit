<?php

declare(strict_types=1);

namespace App\Domain\ActivityLog\Entities;

use App\Domain\ActivityLog\Enums\ActivityType;

final class ActivityLog
{
    public function __construct(
        public readonly ?string   $id,
        public readonly string    $ticketId,
        public readonly string    $actorId,
        public readonly ActivityType $action,
        public readonly array     $payload,
        public readonly ?string   $createdAt,
    ) {}
}
