<?php

declare(strict_types=1);

namespace App\Infrastructure\Measurements\Persistence;

use App\Domain\Measurements\Entities\MeasurementEntity;
use App\Domain\Measurements\Repositories\MeasurementRepositoryInterface;
use App\Infrastructure\Measurements\Models\Measurement;
use App\Infrastructure\Shared\Traits\EntityMapper;

final class EloquentMeasurementRepository implements MeasurementRepositoryInterface
{
    use EntityMapper;

    /**
     * @return MeasurementEntity[]
     */
    public function getByChildId(int $childId): MeasurementEntity
    {
        $models = Measurement::query()->where('child_id', $childId)->get();

        return $this->mapToEntity($models, MeasurementEntity::class);
    }

    /**
     * @return MeasurementEntity[]
     */
    public function getAllGeoTagged(): MeasurementEntity
    {
        $models = Measurement::query()
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get();

        return $this->mapToEntity($models, MeasurementEntity::class);
    }

    public function findById(int $id): ?MeasurementEntity
    {
        $model = Measurement::query()->find($id);

        if ($model === null) {
            return null;
        }

        return $this->mapToEntity($model, MeasurementEntity::class);
    }

    public function create(MeasurementEntity $entity): MeasurementEntity
    {
        $model = Measurement::query()->create([
            'child_id' => $entity->childId,
            'date'     => $entity->date,
            'height'   => $entity->height,
            'weight'   => $entity->weight,
            'lat'      => $entity->lat,
            'lng'      => $entity->lng,
            'z_score'  => $entity->zScore,
            'status'   => $entity->status,
        ]);

        return $this->mapToEntity($model, MeasurementEntity::class);
    }

    public function update(MeasurementEntity $entity): MeasurementEntity
    {
        $model = Measurement::query()->findOrFail($entity->id);

        $model->update([
            'child_id' => $entity->childId,
            'date'     => $entity->date,
            'height'   => $entity->height,
            'weight'   => $entity->weight,
            'lat'      => $entity->lat,
            'lng'      => $entity->lng,
            'z_score'  => $entity->zScore,
            'status'   => $entity->status,
        ]);

        return $this->mapToEntity($model, MeasurementEntity::class);
    }

    public function delete(int $id): bool
    {
        return (bool) Measurement::query()->where('id', $id)->delete();
    }
}
