<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\SendPasswordResetLink;

final readonly class SendPasswordResetLinkCommand
{
    public function __construct(
        public string $email
    ) {}
}
