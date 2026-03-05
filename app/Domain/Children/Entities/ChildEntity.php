<?php

declare(strict_types=1);

namespace App\Domain\Children\Entities;

final readonly class ChildEntity
{
    public function __construct(
        public ?int $id,
        public int $posyanduId,
        public string $nik,
        public string $name,
        public string $dob,
        public string $gender,
        public string $parentName,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    public static function create(
        int $posyanduId,
        string $nik,
        string $name,
        string $dob,
        string $gender,
        string $parentName,
    ): self {
        return new self(
            id: null,
            posyanduId: $posyanduId,
            nik: $nik,
            name: $name,
            dob: $dob,
            gender: $gender,
            parentName: $parentName,
            createdAt: now()->toIso8601String(),
            updatedAt: now()->toIso8601String(),
        );
    }
}
