<?php

declare(strict_types=1);

namespace App\Domain\Child\Exceptions;

use RuntimeException;

final class ChildNotFoundException extends RuntimeException
{
    public function __construct(string $identifier = '')
    {
        parent::__construct(
            $identifier !== ''
                ? "Child not found: {$identifier}"
                : 'Child not found.'
        );
    }
}
