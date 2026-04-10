<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\DeleteTicket;

use App\Domain\Authorization\Enums\UserRole;

final readonly class DeleteTicketCommand
{
    public function __construct(
        public string $ticketId,
        public string $actorId,
        public UserRole $actorProjectRole,
    ) {}
}
