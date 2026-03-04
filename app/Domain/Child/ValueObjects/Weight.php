<?php

declare(strict_types=1);

namespace App\Domain\Child\ValueObjects;

use InvalidArgumentException;

final readonly class Weight
{
    public function __construct(public float $kg)
    {
        if ($kg <= 0) {
            throw new InvalidArgumentException('Weight must be greater than zero.');
        }

        if ($kg > 200) {
            throw new InvalidArgumentException('Weight exceeds reasonable biological limits for a child.');
        }
    }

    public function value(): float
    {
        return $this->kg;
    }
}
