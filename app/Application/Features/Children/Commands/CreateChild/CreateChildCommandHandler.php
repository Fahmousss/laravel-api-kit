<?php

declare(strict_types=1);

namespace App\Application\Features\Children\Commands\CreateChild;

use App\Application\Features\Children\DTOs\ChildDTO;
use App\Domain\Children\Entities\ChildEntity;
use App\Domain\Children\Repositories\ChildRepositoryInterface;

final readonly class CreateChildCommandHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {}

    public function handle(CreateChildCommand $command): ChildDTO
    {
        $entity = ChildEntity::create(
            posyanduId: $command->posyanduId,
            nik: $command->nik,
            name: $command->name,
            dob: $command->dob,
            gender: $command->gender,
            parentName: $command->parentName,
        );

        $savedEntity = $this->childRepository->create($entity);

        return new ChildDTO(
            id: $savedEntity->id,
            posyanduId: $savedEntity->posyanduId,
            nik: $savedEntity->nik,
            name: $savedEntity->name,
            dob: $savedEntity->dob,
            gender: $savedEntity->gender,
            parentName: $savedEntity->parentName,
            createdAt: $savedEntity->createdAt ?? now()->toIso8601String(),
        );
    }
}
