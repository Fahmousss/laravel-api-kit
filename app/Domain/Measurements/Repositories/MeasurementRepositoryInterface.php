<?php

declare(strict_types=1);

namespace App\Domain\Measurements\Repositories;

use App\Domain\Measurements\Entities\MeasurementEntity;
use App\Domain\Shared\Pagination\PaginatedResult;

interface MeasurementRepositoryInterface
{
    /**
     * @return MeasurementEntity[]
     */
    public function getByChildId(int $childId): array;

    /**
     * @return MeasurementEntity[]
     */
    public function getAllGeoTagged(?int $posyanduId = null, ?string $kelurahan = null): array;

    public function findById(int $id): ?MeasurementEntity;

    public function create(MeasurementEntity $entity): MeasurementEntity;

    public function update(MeasurementEntity $entity): MeasurementEntity;

    public function delete(int $id): bool;

    public function getAllPaginated(int $page, int $perPage): PaginatedResult;
}
