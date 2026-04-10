<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\RegisterUser;

final readonly class RegisterUserCommand
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}
}
