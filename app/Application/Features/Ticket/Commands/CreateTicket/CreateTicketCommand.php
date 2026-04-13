<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\CreateTicket;

use App\Application\Features\Ticket\DTOs\CreateTicketDTO;
use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class CreateTicketCommand
{
    public function __construct(
        public ActorContext $actor,
        public CreateTicketDTO $dto,
    ) {}
}
