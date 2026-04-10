<?php

declare(strict_types=1);

namespace App\Domain\Ticket\Exceptions;

use App\Domain\Ticket\Enums\TicketStatus;
use DomainException;

final class InvalidStatusTransitionException extends DomainException
{
    public static function fromTo(TicketStatus $from, TicketStatus $to): self
    {
        return new self(
            "Cannot transition ticket from [{$from->value}] to [{$to->value}].",
            422
        );
    }
}
