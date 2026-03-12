<?php

declare(strict_types=1);

namespace App\Application\Features\Children\Queries\GetAllChildren;

final readonly class GetAllChildrenQuery
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 15,
    ) {}
}
