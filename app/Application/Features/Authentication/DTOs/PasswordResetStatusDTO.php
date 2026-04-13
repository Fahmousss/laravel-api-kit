<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\DTOs;

final readonly class PasswordResetStatusDTO
{
    public function __construct(
        public bool $success,
        public string $message,
    ) {}

    public static function success(string $message): self
    {
        return new self(success: true, message: $message);
    }

    public static function failure(string $message): self
    {
        return new self(success: false, message: $message);
    }
}
