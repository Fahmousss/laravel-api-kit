<?php

declare(strict_types=1);

namespace App\Domain\Measurements\Entities;

final readonly class MeasurementEntity
{
    public function __construct(
        public ?int $id,
        public int $childId,
        public string $date,
        public float $height,
        public ?float $weight = null,
        public ?float $lat = null,
        public ?float $lng = null,
        public ?float $zScore = null,
        public ?string $status = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    public static function create(
        int $childId,
        string $date,
        float $height,
        ?float $weight = null,
        ?float $lat = null,
        ?float $lng = null,
        ?float $zScore = null,
        ?string $status = null,
    ): self {
        return new self(
            id: null,
            childId: $childId,
            date: $date,
            height: $height,
            weight: $weight,
            lat: $lat,
            lng: $lng,
            zScore: $zScore,
            status: $status,
            createdAt: now()->toIso8601String(),
            updatedAt: now()->toIso8601String(),
        );
    }
}
