<?php

declare(strict_types=1);

namespace App\Domain\Location\ValueObjects;

use InvalidArgumentException;

final readonly class GeoCoordinate
{
    public function __construct(
        public float $latitude,
        public float $longitude
    ) {
        if ($latitude < -90 || $latitude > 90) {
            throw new InvalidArgumentException('Latitude must be between -90 and 90 degrees.');
        }

        if ($longitude < -180 || $longitude > 180) {
            throw new InvalidArgumentException('Longitude must be between -180 and 180 degrees.');
        }
    }

    /**
     * @return array<string, float>
     */
    public function toArray(): array
    {
        return [
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
