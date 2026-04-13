<?php

declare(strict_types=1);

namespace App\Application\Features\Ticket\Commands\TransitionStatus;

use App\Application\Features\Ticket\DTOs\TransitionTicketStatusDTO;
use App\Domain\Authorization\ValueObjects\ActorContext;

final readonly class TransitionStatusCommand
{
    public function __construct(
        public ActorContext $actor,
        public TransitionTicketStatusDTO $dto,
    ) {}
}
