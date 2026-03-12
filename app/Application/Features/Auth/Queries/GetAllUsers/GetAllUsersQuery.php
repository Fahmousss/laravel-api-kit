<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Queries\GetAllUsers;

final readonly class GetAllUsersQuery
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 15,
    ) {}
}
