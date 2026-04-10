<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\UpdateTicket;

use App\Application\Features\Ticket\DTOs\UpdateTicketDTO;

final readonly class UpdateTicketCommand
{
    public function __construct(
        public UpdateTicketDTO $dto,
    ) {}
}
