<?php

declare(strict_types=1);

namespace App\Application\Features\Children\Commands\CreateChild;

final readonly class CreateChildCommand
{
    public function __construct(
        public int $posyanduId,
        public string $nik,
        public string $name,
        public string $dob,
        public string $gender,
        public string $parentName,
    ) {}
}
