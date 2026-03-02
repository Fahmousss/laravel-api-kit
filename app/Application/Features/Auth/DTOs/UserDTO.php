<?php

declare(strict_types=1);

namespace App\Application\Auth\DTOs;

use Spatie\LaravelData\Data;

final class UserDTO extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $emailVerifiedAt,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        public readonly ?string $token = null,
    ) {}
}
