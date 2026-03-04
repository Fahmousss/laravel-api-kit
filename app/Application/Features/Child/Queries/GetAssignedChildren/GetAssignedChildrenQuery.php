<?php

declare(strict_types=1);

namespace App\Application\Features\Child\Queries\GetAssignedChildren;

final class GetAssignedChildrenQuery
{
    public function __construct(
        public readonly string $posyanduId,
    ) {}
}
