<?php

declare(strict_types=1);

namespace App\Application\Features\Posyandus\Queries\GetPosyandusByUserId;

final readonly class GetPosyandusByUserIdQuery
{
    public function __construct(
        public int $userId,
        public int $page = 1,
        public int $perPage = 15,
    ) {}
}
