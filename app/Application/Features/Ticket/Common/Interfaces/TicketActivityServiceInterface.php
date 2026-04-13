<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Common\Interfaces;

use App\Domain\ActivityLog\Enums\ActivityType;

interface TicketActivityServiceInterface
{
    public function log(string $ticketId, string $actorId, ActivityType $action, array $payload): void;
}
