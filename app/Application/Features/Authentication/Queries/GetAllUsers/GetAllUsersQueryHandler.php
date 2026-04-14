<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Queries\GetAllUsers;

use App\Application\Features\Authentication\DTOs\UserDTO;
use App\Application\Features\Authorization\Common\Interfaces\SystemRoleResolverInterface;
use App\Domain\Authentication\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;

final readonly class GetAllUsersQueryHandler
{
    public function __construct(
        private UserRepositoryInterface     $userRepository,
        private SystemRoleResolverInterface $roleResolver,
    ) {}

    public function handle(GetAllUsersQuery $query): PaginatedResult
    {
        $paginatedResult = $this->userRepository->paginate(
            filters: [],
            page: $query->page,
            perPage: $query->perPage
        );

        $dtos = array_map(fn ($entity): UserDTO => new UserDTO(
            id:              $entity->id,
            name:            $entity->name,
            email:           $entity->email,
            systemRole:      $this->roleResolver->resolveForEmail($entity->email),
            emailVerifiedAt: $entity->emailVerifiedAt,
            createdAt:       $entity->createdAt ?? now()->toIso8601String(),
            updatedAt:       $entity->updatedAt ?? now()->toIso8601String(),
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
