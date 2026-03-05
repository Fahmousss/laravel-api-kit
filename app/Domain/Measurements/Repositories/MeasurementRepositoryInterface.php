<?php

declare(strict_types=1);

namespace App\Domain\Measurements\Repositories;

use App\Domain\Measurements\Entities\MeasurementEntity;

interface MeasurementRepositoryInterface
{
    /**
     * @return MeasurementEntity[]
     */
    public function getByChildId(int $childId): MeasurementEntity;

    /**
     * @return MeasurementEntity[]
     */
    public function getAllGeoTagged(): MeasurementEntity;

    public function findById(int $id): ?MeasurementEntity;

    public function create(MeasurementEntity $entity): MeasurementEntity;

    public function update(MeasurementEntity $entity): MeasurementEntity;

    public function delete(int $id): bool;
}
