<?php

declare(strict_types=1);

namespace App\Application\Features\Posyandus\Queries\GetPosyandus;

final readonly class GetPosyandusQuery
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 15,
    ) {}
}
