<?php

declare(strict_types=1);

namespace App\Domain\Child\Entities;

/**
 * Pure domain entity — no framework dependencies.
 */
final class ChildEntity
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $posyanduId,
        public readonly string $name,
        public readonly ?string $nik,
        public readonly string $dateOfBirth,
        public readonly string $gender,
    ) {}
}
