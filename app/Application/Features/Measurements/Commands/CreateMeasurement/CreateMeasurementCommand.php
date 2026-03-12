<?php

declare(strict_types=1);

namespace App\Application\Features\Measurements\Commands\CreateMeasurement;

use Illuminate\Support\Carbon;

final readonly class CreateMeasurementCommand
{
    public function __construct(
        public int $childId,
        public Carbon $measurementDate,
        public float $heightCm,
        public string $position,
        public ?float $weightKg = null,
        public ?float $lat = null,
        public ?float $lng = null,
    ) {}
}
