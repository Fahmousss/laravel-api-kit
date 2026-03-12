<?php

declare(strict_types=1);

namespace App\Infrastructure\Measurements\Persistence;

use App\Domain\Measurements\Entities\MeasurementEntity;
use App\Domain\Measurements\Repositories\MeasurementRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;
use App\Infrastructure\Measurements\Models\Measurement;
use App\Infrastructure\Shared\Traits\EntityMapper;

final class EloquentMeasurementRepository implements MeasurementRepositoryInterface
{
    use EntityMapper;

    /**
     * @return MeasurementEntity[]
     */
    public function getByChildId(int $childId): array
    {
        $models = Measurement::query()->where('child_id', $childId)->get();

        return $models->map(fn (Measurement $model): object|array => $this->mapToEntity($model, MeasurementEntity::class))->all();
    }

    /**
     * @return MeasurementEntity[]
     */
    public function getAllGeoTagged(?int $posyanduId = null, ?string $kelurahan = null): array
    {
        $query = Measurement::query()
            ->whereNotNull('lat')
            ->whereNotNull('lng');

        if ($posyanduId !== null) {
            $query->whereHas('child', function ($q) use ($posyanduId): void {
                $q->where('posyandu_id', $posyanduId);
            });
        }

        if ($kelurahan !== null) {
            $query->whereHas('child.posyandu', function ($q) use ($kelurahan): void {
                $q->where('location', $kelurahan);
            });
        }

        $models = $query->get();

        return $models->map(fn (Measurement $model): object|array => $this->mapToEntity($model, MeasurementEntity::class))->all();
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

    public function getAllPaginated(int $page, int $perPage): PaginatedResult
    {
        $paginator = Measurement::query()->with('child')->latest('date')->paginate(perPage: $perPage, page: $page);

        $entities = array_map(
            fn (Measurement $model): array|object => $this->mapToEntity($model, MeasurementEntity::class),
            $paginator->items()
        );

        return new PaginatedResult(
            items: $entities,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage()
        );
    }
}
