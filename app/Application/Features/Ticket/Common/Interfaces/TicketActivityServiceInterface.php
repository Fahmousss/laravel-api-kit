<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Common\Interfaces;

interface TicketActivityServiceInterface
{
    public function log(string $ticketId, string $actorId, string $action, array $payload): void;
}
