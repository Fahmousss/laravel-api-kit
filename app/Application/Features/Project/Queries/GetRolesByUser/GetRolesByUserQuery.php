<?php

declare(strict_types=1);

namespace App\Application\Features\Project\Queries\GetRolesByUser;

final readonly class GetRolesByUserQuery
{
    public function __construct(
        public string $userId
    ) {}
}

