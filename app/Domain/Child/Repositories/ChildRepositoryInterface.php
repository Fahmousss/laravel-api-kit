<?php

declare(strict_types=1);

namespace App\Domain\Child\Repositories;

use App\Domain\Child\Entities\ChildEntity;

interface ChildRepositoryInterface
{
    public function findById(string $id): ?ChildEntity;

    public function save(ChildEntity $child): ChildEntity;
}
