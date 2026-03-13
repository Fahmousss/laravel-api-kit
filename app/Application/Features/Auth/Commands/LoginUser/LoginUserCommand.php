<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\LoginUser;

final readonly class LoginUserCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}
}
