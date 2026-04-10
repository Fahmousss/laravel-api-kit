<?php

namespace App\Domain\Ticket\Exceptions;

class TicketNotFoundException extends \DomainException
{
    public static function withId(string $id): self
    {
        return new self("Ticket [{$id}] not found.", 404);
    }
}
