<?php

declare(strict_types=1);

namespace App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary;

use App\Domain\Measurements\Repositories\MeasurementRepositoryInterface;

final readonly class GetGeoTaggedStuntingSummaryQueryHandler
{
    public function __construct(
        private MeasurementRepositoryInterface $measurementRepository,
    ) {}

    /**
     * @return array<int, array{id: int, lat: float, lng: float, status: null|string}>
     */
    public function handle(): array
    {
        $entities = $this->measurementRepository->getAllGeoTagged();

        return array_map(static fn ($entity): array => [
            'id'     => $entity->id,
            'lat'    => $entity->lat,
            'lng'    => $entity->lng,
            'status' => $entity->status,
        ], $entities);
    }
}
