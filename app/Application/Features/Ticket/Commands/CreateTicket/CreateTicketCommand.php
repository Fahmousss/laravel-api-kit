<?php

namespace App\Application\Features\Ticket\Commands\CreateTicket;

use App\Application\Features\Ticket\DTOs\CreateTicketDTO;

readonly class CreateTicketCommand
{
    public function __construct(
        public CreateTicketDTO $dto,
    ) {}
}
