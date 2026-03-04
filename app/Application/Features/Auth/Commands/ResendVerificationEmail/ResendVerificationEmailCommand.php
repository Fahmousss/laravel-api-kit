<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\ResendVerificationEmail;

final readonly class ResendVerificationEmailCommand
{
    public function __construct(
        public string $email
    ) {}
}
