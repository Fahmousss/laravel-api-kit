<?php

declare(strict_types=1);

namespace App\Domain\Authorization\Exceptions;

use App\Domain\Authorization\Enums\SystemAction;
use DomainException;

final class UnauthorizedActionException extends DomainException
{
    public static function forAction(SystemAction $action): self
    {
        return new self("You are not authorized to perform [{$action->value}].", 403);
    }
}
