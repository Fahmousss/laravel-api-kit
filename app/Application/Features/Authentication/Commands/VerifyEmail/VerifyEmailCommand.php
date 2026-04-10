<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\VerifyEmail;

final readonly class VerifyEmailCommand
{
    public function __construct(
        public int $userId
    ) {}
}
