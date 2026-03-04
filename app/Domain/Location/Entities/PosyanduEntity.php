<?php

declare(strict_types=1);

namespace App\Domain\Location\Entities;

/**
 * Pure domain entity — no framework dependencies.
 */
final class PosyanduEntity
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $name,
        public readonly ?\App\Domain\Location\ValueObjects\GeoCoordinate $coordinates,
    ) {}
}
