<?php

namespace App\Infrastructure\Ticket\Services;

use App\Application\Features\Ticket\Common\Interfaces\TicketActivityServiceInterface;
use App\Infrastructure\ActivityLog\Models\ActivityLog as ActivityLogModel;

class TicketActivityService implements TicketActivityServiceInterface
{
    public function log(string $ticketId, string $actorId, string $action, array $payload): void
    {
        ActivityLogModel::create([
            'ticket_id'  => $ticketId,
            'actor_id'   => $actorId,
            'action'     => $action,
            'payload'    => $payload,
            'created_at' => now(),
        ]);
    }
}
