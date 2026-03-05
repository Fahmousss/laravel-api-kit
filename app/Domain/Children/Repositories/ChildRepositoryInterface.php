<?php

declare(strict_types=1);

namespace App\Domain\Children\Repositories;

use App\Domain\Children\Entities\ChildEntity;

interface ChildRepositoryInterface
{
    /**
     * @return ChildEntity[]
     */
    public function getByPosyanduId(int $posyanduId): ChildEntity;

    public function findById(int $id): ?ChildEntity;

    public function create(ChildEntity $entity): ChildEntity;

    public function update(ChildEntity $entity): ChildEntity;

    public function delete(int $id): bool;
}
