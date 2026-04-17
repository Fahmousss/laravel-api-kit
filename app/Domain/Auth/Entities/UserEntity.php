<?php

declare(strict_types=1);

namespace App\Domain\Auth\Entities;


/**
 * Pure domain entity — no framework dependencies.
 */
final readonly class UserEntity
{
    public function __construct(
        public ?int $id,
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
