<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\DeleteTicket;

use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class DeleteTicketCommand
{
    public function __construct(
        public string $ticketId,
        public ActorContext $actor,
    ) {}
}
