<?php

declare(strict_types=1);

namespace App\Domain\Location\Exceptions;

use RuntimeException;

final class PosyanduNotFoundException extends RuntimeException
{
    public function __construct(string $identifier = '')
    {
        parent::__construct(
            $identifier !== ''
                ? "Posyandu not found: {$identifier}"
                : 'Posyandu not found.'
        );
    }
}
