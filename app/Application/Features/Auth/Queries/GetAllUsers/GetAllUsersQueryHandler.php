<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Queries\GetAllUsers;

use App\Application\Features\Auth\DTOs\UserDTO;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

final readonly class GetAllUsersQueryHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(GetAllUsersQuery $query): PaginatedResult
    {
        $paginatedResult = $this->userRepository->getAllPaginated(
            page: $query->page,
            perPage: $query->perPage
        );

        $dtos = array_map(static fn ($entity): UserDTO => new UserDTO(
            id: $entity->id,
            name: $entity->name,
            email: $entity->email,
            emailVerifiedAt: $entity->emailVerifiedAt,
            createdAt: $entity->createdAt ?? now()->toIso8601String(),
            updatedAt: $entity->updatedAt ?? now()->toIso8601String(),
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
