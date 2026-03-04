<?php

declare(strict_types=1);

namespace App\Application\Features\Child\Commands\RegisterChild;

final class RegisterChildCommand
{
    public function __construct(
        public readonly string $posyanduId,
        public readonly string $name,
        public readonly ?string $nik,
        public readonly string $dateOfBirth,
        public readonly string $gender,
    ) {}
}
