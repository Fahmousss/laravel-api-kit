<?php

declare(strict_types=1);

namespace App\Domain\Authentication\Repositories;

use App\Domain\Authentication\Entities\UserEntity;
use App\Domain\Shared\Pagination\PaginatedResult;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserEntity;

    public function findById(string $id): ?UserEntity;

    public function save(UserEntity $user): UserEntity;

    public function markEmailAsVerified(string $userId): void;

    public function getAllPaginated(int $page, int $perPage): PaginatedResult;
}
