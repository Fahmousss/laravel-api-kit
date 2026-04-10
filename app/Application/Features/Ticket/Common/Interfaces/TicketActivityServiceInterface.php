<?php

namespace App\Application\Features\Ticket\Common\Interfaces;

interface TicketActivityServiceInterface
{
    public function log(string $ticketId, string $actorId, string $action, array $payload): void;
}
