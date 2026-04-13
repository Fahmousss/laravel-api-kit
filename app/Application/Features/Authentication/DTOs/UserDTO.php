<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\DTOs;

use App\Domain\Authorization\Enums\SystemRole;

final readonly class UserDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public SystemRole $systemRole,
        public ?string $emailVerifiedAt,
        public string $createdAt,
        public string $updatedAt,
        public ?string $token = null
    ) {}
}
