<?php

declare(strict_types=1);

namespace App\Application\Features\Children\Queries\GetAllChildren;

use App\Application\Features\Children\DTOs\ChildDTO;
use App\Domain\Children\Repositories\ChildRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

final readonly class GetAllChildrenQueryHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {}

    public function handle(GetAllChildrenQuery $query): PaginatedResult
    {
        $paginatedResult = $this->childRepository->getAllPaginated(
            page: $query->page,
            perPage: $query->perPage
        );

        $dtos = array_map(static fn ($entity): ChildDTO => new ChildDTO(
            id: $entity->id,
            posyanduId: $entity->posyanduId,
            nik: $entity->nik,
            name: $entity->name,
            dob: $entity->dob,
            gender: $entity->gender,
            parentName: $entity->parentName,
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
