<?php

declare(strict_types=1);

namespace App\Application\Features\Posyandus\Commands\CreatePosyandu;

final readonly class CreatePosyanduCommand
{
    public function __construct(
        public string $name,
        public string $district,
        public ?string $location = null,
        public ?float $lat = null,
        public ?float $lng = null,
    ) {}
}
