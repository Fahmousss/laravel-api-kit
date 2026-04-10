<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\LogoutUser;

final readonly class LogoutUserCommand
{
    public function __construct(
        public string $token
    ) {}
}
