<?php

declare(strict_types=1);

namespace App\Application\Features\Children\Queries\GetChildrenByPosyandu;

final readonly class GetChildrenByPosyanduQuery
{
    public function __construct(
        public int $posyanduId,
    ) {}
}
