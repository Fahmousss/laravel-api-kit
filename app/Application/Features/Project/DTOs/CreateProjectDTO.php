<?php

namespace App\Application\Features\Project\DTOs;

readonly class CreateProjectDTO
{
    public function __construct(
        public string  $ownerId,
        public string  $name,
        public string  $slug,
        public ?string $description,
    ) {}
}
