<?php

namespace App\Application\Features\Ticket\Commands\TransitionStatus;

use App\Application\Features\Ticket\DTOs\TransitionTicketStatusDTO;

readonly class TransitionStatusCommand
{
    public function __construct(
        public TransitionTicketStatusDTO $dto,
    ) {}
}
