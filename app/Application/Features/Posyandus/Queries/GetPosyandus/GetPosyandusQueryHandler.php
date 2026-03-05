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

    /**
     * @return PosyanduDTO[]
     */
    public function handle(GetPosyandusQuery $query): array
    {
        $entities = $this->posyanduRepository->getAll();

        return array_map(static fn ($entity) => new PosyanduDTO(
            id: $entity->id,
            name: $entity->name,
            district: $entity->district,
            location: $entity->location,
            lat: $entity->lat,
            lng: $entity->lng,
            createdAt: $entity->createdAt ?? now()->toIso8601String(),
        ), $entities);
    }
}
