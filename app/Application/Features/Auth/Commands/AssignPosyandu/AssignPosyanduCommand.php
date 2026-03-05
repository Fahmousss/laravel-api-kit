<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\AssignPosyandu;

final readonly class AssignPosyanduCommand
{
    public function __construct(
        public int $userId,
        public int $posyanduId,
    ) {}
}
