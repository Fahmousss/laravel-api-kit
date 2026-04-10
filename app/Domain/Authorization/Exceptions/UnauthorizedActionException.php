<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Exceptions;

use DomainException;

final class UnauthorizedActionException extends DomainException
{
    public static function forAction(string $action): self
    {
        return new self("You are not authorized to perform [{$action}].", 403);
    }
}
