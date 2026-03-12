<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\RevokeRole;

use App\Domain\Auth\Enums\Role;

final readonly class RevokeRoleCommand
{
    public function __construct(
        public int $userId,
        public Role $role,
    ) {}
}
