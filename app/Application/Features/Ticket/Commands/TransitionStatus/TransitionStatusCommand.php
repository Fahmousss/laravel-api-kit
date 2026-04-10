<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\TransitionStatus;

use App\Application\Features\Ticket\DTOs\TransitionTicketStatusDTO;

final readonly class TransitionStatusCommand
{
    public function __construct(
        public TransitionTicketStatusDTO $dto,
    ) {}
}
