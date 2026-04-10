<?php

namespace App\Domain\Project\Exceptions;

class ProjectNotFoundException extends \DomainException
{
    public static function withId(string $id): self
    {
        return new self("Project [{$id}] not found.", 404);
    }
}
