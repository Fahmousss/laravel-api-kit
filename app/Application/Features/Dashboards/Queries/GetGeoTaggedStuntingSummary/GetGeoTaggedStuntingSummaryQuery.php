<?php

declare(strict_types=1);

namespace App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary;

final readonly class GetGeoTaggedStuntingSummaryQuery
{
    // Fetches aggregated map data for Admin and Stakeholders, optionally filtered by posyandu
    public function __construct(
        public ?int $posyanduId = null,
        public ?string $kelurahan = null,
    ) {}
}
