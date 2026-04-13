<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\UpdateTicket;

use App\Application\Features\Ticket\DTOs\UpdateTicketDTO;
use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class UpdateTicketCommand
{
    public function __construct(
        public ActorContext $actor,
        public UpdateTicketDTO $dto,
    ) {}
}
