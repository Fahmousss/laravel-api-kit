<?php

declare(strict_types=1);

namespace App\Application\Features\Child\Commands\LogMeasurement;

final class LogMeasurementCommand
{
    public function __construct(
        public readonly string $childId,
        public readonly float $heightCm,
        public readonly float $weightKg,
        public readonly string $measuredAt,
    ) {}
}
