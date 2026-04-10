<?php

namespace App\Domain\Ticket\Exceptions;

use App\Domain\Ticket\Enums\TicketStatus;

class InvalidStatusTransitionException extends \DomainException
{
    public static function fromTo(TicketStatus $from, TicketStatus $to): self
    {
        return new self(
            "Cannot transition ticket from [{$from->value}] to [{$to->value}].",
            422
        );
    }
}
