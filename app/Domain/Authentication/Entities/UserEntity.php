<?php

declare(strict_types=1);

namespace App\Domain\Authentication\Entities;

/**
 * Pure domain entity — no framework dependencies.
 *
 * SystemRole is intentionally absent: it is an authorization concern
 * resolved at runtime from config, not an identity property of the user.
 */
final readonly class UserEntity
{
    public function __construct(
        public ?string $id,
        public string $name,
        public string $email,
        public string $password,
        public ?string $emailVerifiedAt = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    public static function create(string $name, string $email, string $password): self
    {
        return new self(
            id: null,
            name: $name,
            email: $email,
            password: $password,
            emailVerifiedAt: null,
            createdAt: now()->toIso8601String(),
            updatedAt: now()->toIso8601String(),
        );
    }
}
