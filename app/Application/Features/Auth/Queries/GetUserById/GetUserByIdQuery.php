<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Queries\GetUserById;

final readonly class GetUserByIdQuery
{
    public function __construct(
        public int $id
    ) {}
}
