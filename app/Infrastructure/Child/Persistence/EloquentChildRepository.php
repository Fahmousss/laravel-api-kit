<?php

declare(strict_types=1);

namespace App\Infrastructure\Child\Persistence;

use App\Domain\Child\Entities\ChildEntity;
use App\Domain\Child\Repositories\ChildRepositoryInterface;
use App\Infrastructure\Child\Models\Child;
use App\Infrastructure\Shared\Traits\EntityMapper;

final class EloquentChildRepository implements ChildRepositoryInterface
{
    use EntityMapper;

    public function findById(string $id): ?ChildEntity
    {
        $model = Child::find($id);

        if (! $model) {
            return null;
        }

        return $this->mapToEntity($model, ChildEntity::class);
    }

    public function save(ChildEntity $child): ChildEntity
    {
        $model = Child::updateOrCreate(
            ['id' => $child->id],
            [
                'posyandu_id'   => $child->posyanduId,
                'name'          => $child->name,
                'nik'           => $child->nik,
                'date_of_birth' => $child->dateOfBirth,
                'gender'        => $child->gender,
            ]
        );

        return $this->mapToEntity($model, ChildEntity::class);
    }
}
