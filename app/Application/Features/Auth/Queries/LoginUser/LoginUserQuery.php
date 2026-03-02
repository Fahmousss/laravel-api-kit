<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Queries\LoginUser;

final readonly class LoginUserQuery
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
