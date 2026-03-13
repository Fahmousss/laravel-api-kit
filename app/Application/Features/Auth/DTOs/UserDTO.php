<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\DTOs;

final readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $emailVerifiedAt,
        public string $createdAt,
        public string $updatedAt
    ) {}
}
