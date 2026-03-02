<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exceptions;

use RuntimeException;

final class UserNotFoundException extends RuntimeException
{
    public function __construct(string $identifier = '')
    {
        parent::__construct(
            $identifier !== ''
                ? 'User not found: '.$identifier
                : 'User not found.'
        );
    }
}
