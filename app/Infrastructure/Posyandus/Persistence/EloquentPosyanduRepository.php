<?php

declare(strict_types=1);

namespace App\Infrastructure\Posyandus\Persistence;

use App\Domain\Posyandus\Entities\PosyanduEntity;
use App\Domain\Posyandus\Repositories\PosyanduRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;
use App\Infrastructure\Posyandus\Models\Posyandu;
use App\Infrastructure\Shared\Traits\EntityMapper;

final class EloquentPosyanduRepository implements PosyanduRepositoryInterface
{
    use EntityMapper;

    public function getAllPaginated(int $page = 1, int $perPage = 15): PaginatedResult
    {
        $paginator = Posyandu::query()->paginate(perPage: $perPage, page: $page);

        // Map the items into Entities
        $items = $this->mapToEntity($paginator->getCollection(), PosyanduEntity::class);

        return new PaginatedResult(
            items: is_array($items) ? $items : [$items],
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage()
        );
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
