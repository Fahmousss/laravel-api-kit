<?php

namespace App\Application\Features\Ticket\Commands\DeleteTicket;

use App\Domain\Authorization\Enums\UserRole;

readonly class DeleteTicketCommand
{
    public function __construct(
        public string $ticketId,
        public string $actorId,
        public UserRole $actorProjectRole,
    ) {}
}
