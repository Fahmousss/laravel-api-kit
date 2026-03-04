<?php

declare(strict_types=1);

namespace App\Domain\Child\ValueObjects;

use InvalidArgumentException;

final readonly class Height
{
    public function __construct(public float $cm)
    {
        if ($cm <= 0) {
            throw new InvalidArgumentException('Height must be greater than zero.');
        }

        if ($cm > 250) {
            throw new InvalidArgumentException('Height exceeds reasonable biological limits.');
        }
    }

    public function value(): float
    {
        return $this->cm;
    }
}
