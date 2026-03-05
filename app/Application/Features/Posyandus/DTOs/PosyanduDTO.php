<?php

declare(strict_types=1);

namespace App\Application\Features\Posyandus\DTOs;

final readonly class PosyanduDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $district,
        public ?string $location,
        public ?float $lat,
        public ?float $lng,
        public string $createdAt,
    ) {}
}
