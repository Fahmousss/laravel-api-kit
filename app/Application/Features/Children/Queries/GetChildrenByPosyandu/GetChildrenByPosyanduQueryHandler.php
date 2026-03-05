<?php

declare(strict_types=1);

namespace App\Application\Features\Children\Queries\GetChildrenByPosyandu;

use App\Application\Features\Children\DTOs\ChildDTO;
use App\Domain\Children\Repositories\ChildRepositoryInterface;

final readonly class GetChildrenByPosyanduQueryHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {}

    /**
     * @return ChildDTO[]
     */
    public function handle(GetChildrenByPosyanduQuery $query): array
    {
        $entities = $this->childRepository->getByPosyanduId($query->posyanduId);

        return array_map(static fn ($entity) => new ChildDTO(
            id: $entity->id,
            posyanduId: $entity->posyanduId,
            nik: $entity->nik,
            name: $entity->name,
            dob: $entity->dob,
            gender: $entity->gender,
            parentName: $entity->parentName,
            createdAt: $entity->createdAt ?? now()->toIso8601String(),
        ), $entities);
    }
}
