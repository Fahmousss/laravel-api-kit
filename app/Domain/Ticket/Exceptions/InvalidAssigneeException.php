<?php

declare(strict_types=1);

namespace App\Domain\Ticket\Exceptions;

use DomainException;

final class InvalidAssigneeException extends DomainException
{
    public static function notAProjectMember(string $assigneeId, string $projectId): self
    {
        return new self("User [{$assigneeId}] is not a member of project [{$projectId}] and cannot be assigned to this ticket.");
    }
}
