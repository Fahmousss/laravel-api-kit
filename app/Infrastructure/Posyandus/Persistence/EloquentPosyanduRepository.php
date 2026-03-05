<?php

declare(strict_types=1);

namespace App\Infrastructure\Posyandus\Persistence;

use App\Domain\Posyandus\Entities\PosyanduEntity;
use App\Domain\Posyandus\Repositories\PosyanduRepositoryInterface;
use App\Infrastructure\Posyandus\Models\Posyandu;
use App\Infrastructure\Shared\Traits\EntityMapper;

final class EloquentPosyanduRepository implements PosyanduRepositoryInterface
{
    use EntityMapper;

    /**
     * @return PosyanduEntity[]
     */
    public function getAll(): PosyanduEntity
    {
        $models = Posyandu::all();

        return $this->mapToEntity($models, PosyanduEntity::class);
    }

    public function findById(int $id): ?PosyanduEntity
    {
        $model = Posyandu::query()->find($id);

        if ($model === null) {
            return null;
        }

        return $this->mapToEntity($model, PosyanduEntity::class);
    }

    public function create(PosyanduEntity $entity): PosyanduEntity
    {
        $model = Posyandu::query()->create([
            'name'     => $entity->name,
            'district' => $entity->district,
            'location' => $entity->location,
            'lat'      => $entity->lat,
            'lng'      => $entity->lng,
        ]);

        return $this->mapToEntity($model, PosyanduEntity::class);
    }

    public function update(PosyanduEntity $entity): PosyanduEntity
    {
        $model = Posyandu::query()->findOrFail($entity->id);

        $model->update([
            'name'     => $entity->name,
            'district' => $entity->district,
            'location' => $entity->location,
            'lat'      => $entity->lat,
            'lng'      => $entity->lng,
        ]);

        return $this->mapToEntity($model, PosyanduEntity::class);
    }

    public function delete(int $id): bool
    {
        return (bool) Posyandu::query()->where('id', $id)->delete();
    }
}
