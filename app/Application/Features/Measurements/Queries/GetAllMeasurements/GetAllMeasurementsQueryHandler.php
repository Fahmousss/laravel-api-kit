<?php

declare(strict_types=1);

namespace App\Application\Features\Measurements\Queries\GetAllMeasurements;

use App\Application\Features\Measurements\DTOs\MeasurementDTO;
use App\Domain\Measurements\Repositories\MeasurementRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

final readonly class GetAllMeasurementsQueryHandler
{
    public function __construct(
        private MeasurementRepositoryInterface $measurementRepository,
    ) {}

    public function handle(GetAllMeasurementsQuery $query): PaginatedResult
    {
        $paginatedResult = $this->measurementRepository->getAllPaginated(
            page: $query->page,
            perPage: $query->perPage
        );

        $dtos = array_map(static fn ($entity): MeasurementDTO => new MeasurementDTO(
            id: $entity->id,
            childId: $entity->childId,
            date: $entity->date,
            height: $entity->height,
            weight: $entity->weight,
            lat: $entity->lat,
            lng: $entity->lng,
            zScore: $entity->zScore,
            status: $entity->status,
            createdAt: $entity->createdAt ?? now()->toIso8601String(),
        ), $paginatedResult->items);

        return new PaginatedResult(
            items: $dtos,
            total: $paginatedResult->total,
            perPage: $paginatedResult->perPage,
            currentPage: $paginatedResult->currentPage,
            lastPage: $paginatedResult->lastPage
        );
    }
}
