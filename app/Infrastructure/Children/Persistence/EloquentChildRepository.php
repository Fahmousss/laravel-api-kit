<?php

declare(strict_types=1);

namespace App\Infrastructure\Children\Persistence;

use App\Domain\Children\Entities\ChildEntity;
use App\Domain\Children\Repositories\ChildRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;
use App\Infrastructure\Children\Models\Child;
use App\Infrastructure\Shared\Traits\EntityMapper;

final class EloquentChildRepository implements ChildRepositoryInterface
{
    use EntityMapper;

    /**
     * @return ChildEntity[]
     */
    public function getByPosyanduId(int $posyanduId): array
    {
        $models = Child::query()->where('posyandu_id', $posyanduId)->get();

        return $this->mapToEntity($models, ChildEntity::class);
    }

    public function findById(int $id): ?ChildEntity
    {
        $model = Child::query()->find($id);

        if ($model === null) {
            return null;
        }

        return $this->mapToEntity($model, ChildEntity::class);
    }

    public function create(ChildEntity $entity): ChildEntity
    {
        $model = Child::query()->create([
            'posyandu_id' => $entity->posyanduId,
            'nik'         => $entity->nik,
            'name'        => $entity->name,
            'dob'         => $entity->dob,
            'gender'      => $entity->gender,
            'parent_name' => $entity->parentName,
        ]);

        return $this->mapToEntity($model, ChildEntity::class);
    }

    public function update(ChildEntity $entity): ChildEntity
    {
        $model = Child::query()->findOrFail($entity->id);

        $model->update([
            'posyandu_id' => $entity->posyanduId,
            'nik'         => $entity->nik,
            'name'        => $entity->name,
            'dob'         => $entity->dob,
            'gender'      => $entity->gender,
            'parent_name' => $entity->parentName,
        ]);

        return $this->mapToEntity($model, ChildEntity::class);
    }

    public function delete(int $id): bool
    {
        return (bool) Child::query()->where('id', $id)->delete();
    }

    public function getAllPaginated(int $page, int $perPage): PaginatedResult
    {
        $paginator = Child::query()->with('posyandu')->latest()->paginate(perPage: $perPage, page: $page);

        $entities = array_map(
            fn (Child $model): array|object => $this->mapToEntity($model, ChildEntity::class),
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
