<?php

declare(strict_types=1);

namespace App\Domain\Auth\Repositories;

use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Shared\Pagination\PaginatedResult;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserEntity;

    public function findById(string $id): ?UserEntity;

    public function save(UserEntity $user): UserEntity;

    public function getAllPaginated(int $page, int $perPage): PaginatedResult;
}
