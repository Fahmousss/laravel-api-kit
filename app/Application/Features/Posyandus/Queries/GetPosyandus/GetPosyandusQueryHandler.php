<?php

declare(strict_types=1);

namespace App\Application\Features\Posyandus\Queries\GetPosyandus;

use App\Application\Features\Posyandus\DTOs\PosyanduDTO;
use App\Domain\Posyandus\Repositories\PosyanduRepositoryInterface;

final readonly class GetPosyandusQueryHandler
{
    public function __construct(
        private PosyanduRepositoryInterface $posyanduRepository,
    ) {}

    public function handle(GetPosyandusQuery $query): \App\Domain\Shared\Pagination\PaginatedResult
    {
        $paginatedResult = $this->posyanduRepository->getAllPaginated(
            page: $query->page,
            perPage: $query->perPage
        );

        $dtos = array_map(static fn ($entity) => new PosyanduDTO(
            id: $entity->id,
            name: $entity->name,
            district: $entity->district,
            location: $entity->location,
            lat: $entity->lat,
            lng: $entity->lng,
            createdAt: $entity->createdAt ?? now()->toIso8601String(),
        ), $paginatedResult->items);

        return new \App\Domain\Shared\Pagination\PaginatedResult(
            items: $dtos,
            total: $paginatedResult->total,
            perPage: $paginatedResult->perPage,
            currentPage: $paginatedResult->currentPage,
            lastPage: $paginatedResult->lastPage
        );
    }
}
