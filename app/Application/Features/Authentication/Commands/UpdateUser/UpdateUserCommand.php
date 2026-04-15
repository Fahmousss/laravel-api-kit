<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\UpdateUser;

final readonly class UpdateUserCommand
{
    public function __construct(
        public string  $id,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
    ) {}
}
