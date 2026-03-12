<?php

declare(strict_types=1);

namespace App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary;

use App\Domain\Measurements\Entities\MeasurementEntity;
use App\Domain\Measurements\Repositories\MeasurementRepositoryInterface;

final readonly class GetGeoTaggedStuntingSummaryQueryHandler
{
    public function __construct(
        private MeasurementRepositoryInterface $measurementRepository,
    ) {}

    /**
     * @return array<int, array{id: int, lat: float, lng: float, status: null|string}>
     */
    public function handle(GetGeoTaggedStuntingSummaryQuery $query): array
    {
        $entities = $this->measurementRepository->getAllGeoTagged($query->posyanduId, $query->kelurahan);

        return array_map(static fn (MeasurementEntity $entity): array => [
            'id'     => $entity->id,
            'lat'    => $entity->lat,
            'lng'    => $entity->lng,
            'status' => $entity->status,
        ], $entities);
    }
}
