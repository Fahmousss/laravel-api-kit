<?php

declare(strict_types=1);

namespace App\Application\Features\Posyandus\Commands\CreatePosyandu;

use App\Application\Features\Posyandus\DTOs\PosyanduDTO;
use App\Domain\Posyandus\Entities\PosyanduEntity;
use App\Domain\Posyandus\Repositories\PosyanduRepositoryInterface;

final readonly class CreatePosyanduCommandHandler
{
    public function __construct(
        private PosyanduRepositoryInterface $posyanduRepository,
    ) {}

    public function handle(CreatePosyanduCommand $command): PosyanduDTO
    {
        $entity = PosyanduEntity::create(
            name: $command->name,
            district: $command->district,
            location: $command->location,
            lat: $command->lat,
            lng: $command->lng,
        );

        $savedEntity = $this->posyanduRepository->create($entity);

        return new PosyanduDTO(
            id: $savedEntity->id,
            name: $savedEntity->name,
            district: $savedEntity->district,
            location: $savedEntity->location,
            lat: $savedEntity->lat,
            lng: $savedEntity->lng,
            createdAt: $savedEntity->createdAt ?? now()->toIso8601String(),
            updatedAt: $savedEntity->updatedAt ?? now()->toIso8601String(),
        );
    }
}
