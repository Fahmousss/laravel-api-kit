<?php

declare(strict_types=1);

namespace App\Domain\Project\Exceptions;

use DomainException;

final class DuplicateProjectMemberException extends DomainException
{
    public static function forUser(string $userId): self
    {
        return new self("User [{$userId}] is already a member of this project.", 409);
    }
}
