<?php

declare(strict_types=1);

namespace App\Domain\Ticket\Exceptions;

use DomainException;

final class TicketNotFoundException extends DomainException
{
    public static function withId(string $id): self
    {
        return new self("Ticket [{$id}] not found.", 404);
    }
}
