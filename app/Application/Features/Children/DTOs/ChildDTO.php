<?php

declare(strict_types=1);

namespace App\Application\Features\Children\DTOs;

final readonly class ChildDTO
{
    public function __construct(
        public int $id,
        public int $posyanduId,
        public string $nik,
        public string $name,
        public string $dob,
        public string $gender,
        public string $parentName,
        public string $createdAt,
    ) {}
}
