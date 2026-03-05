<?php

declare(strict_types=1);

namespace App\Domain\Children\Exceptions;

use RuntimeException;

final class ChildrenNotFoundException extends RuntimeException
{
    public function __construct(string $identifier = '')
    {
        parent::__construct(
            $identifier !== ''
                ? 'Child not found: '.$identifier
                : 'Child not found.'
        );
    }
}
