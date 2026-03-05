<?php

declare(strict_types=1);

namespace App\Application\Features\Measurements\DTOs;

final readonly class MeasurementDTO
{
    public function __construct(
        public int $id,
        public int $childId,
        public string $date,
        public float $height,
        public ?float $weight,
        public ?float $lat,
        public ?float $lng,
        public ?float $zScore,
        public ?string $status,
        public string $createdAt,
    ) {}
}
