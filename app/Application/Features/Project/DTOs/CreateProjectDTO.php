<?php

declare(strict_types=1);

namespace App\Application\Features\Project\DTOs;

final readonly class CreateProjectDTO
{
    public function __construct(
        public string $ownerId,
        public string $name,
        public string $slug,
        public ?string $description,
    ) {}
}
