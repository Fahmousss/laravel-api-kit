<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\ResetPassword;

final readonly class ResetPasswordCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public string $passwordConfirmation,
        public string $token
    ) {}
}
