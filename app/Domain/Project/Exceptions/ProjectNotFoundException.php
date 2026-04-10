<?php

declare(strict_types=1);

namespace App\Domain\Project\Exceptions;

use DomainException;

final class ProjectNotFoundException extends DomainException
{
    public static function withId(string $id): self
    {
        return new self("Project [{$id}] not found.", 404);
    }
}
