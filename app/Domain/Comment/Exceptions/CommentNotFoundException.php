<?php

declare(strict_types=1);

namespace App\Domain\Comment\Exceptions;

use DomainException;

final class CommentNotFoundException extends DomainException
{
    public static function withId(string $id): self
    {
        return new self("Comment [{$id}] not found.", 404);
    }
}
