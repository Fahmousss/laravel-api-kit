<?php

namespace App\Domain\Project\Exceptions;

class DuplicateProjectMemberException extends \DomainException
{
    public static function forUser(string $userId): self
    {
        return new self("User [{$userId}] is already a member of this project.", 409);
    }
}
