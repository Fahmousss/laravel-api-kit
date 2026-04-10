<?php

namespace App\Application\Features\Ticket\Commands\UpdateTicket;

use App\Application\Features\Ticket\DTOs\UpdateTicketDTO;

readonly class UpdateTicketCommand
{
    public function __construct(
        public UpdateTicketDTO $dto,
    ) {}
}
