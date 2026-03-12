<?php

declare(strict_types=1);

namespace App\Application\Features\Measurements\Queries\GetAllMeasurements;

final readonly class GetAllMeasurementsQuery
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 15,
    ) {}
}
