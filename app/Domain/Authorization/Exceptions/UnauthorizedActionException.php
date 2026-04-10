<?php

namespace App\Domain\Authorization\Exceptions;

class UnauthorizedActionException extends \DomainException
{
    public static function forAction(string $action): self
    {
        return new self("You are not authorized to perform [{$action}].", 403);
    }
}
