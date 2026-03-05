<?php

declare(strict_types=1);

namespace App\Domain\Posyandus\Repositories;

use App\Domain\Posyandus\Entities\PosyanduEntity;
use App\Domain\Shared\Pagination\PaginatedResult;

interface PosyanduRepositoryInterface
{
    public function getAllPaginated(int $page = 1, int $perPage = 15): PaginatedResult;

    public function findById(int $id): ?PosyanduEntity;

    public function create(PosyanduEntity $entity): PosyanduEntity;

    public function update(PosyanduEntity $entity): PosyanduEntity;

    public function delete(int $id): bool;
}
