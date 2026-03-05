<?php

declare(strict_types=1);

namespace App\Domain\Posyandus\Entities;

final readonly class PosyanduEntity
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $district,
        public ?string $location = null,
        public ?float $lat = null,
        public ?float $lng = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    public static function create(
        string $name,
        string $district,
        ?string $location = null,
        ?float $lat = null,
        ?float $lng = null,
    ): self {
        return new self(
            id: null,
            name: $name,
            district: $district,
            location: $location,
            lat: $lat,
            lng: $lng,
            createdAt: now()->toIso8601String(),
            updatedAt: now()->toIso8601String(),
        );
    }
}
