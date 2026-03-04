<?php

declare(strict_types=1);

namespace App\Domain\Location\Repositories;

use App\Domain\Location\Entities\PosyanduEntity;

interface PosyanduRepositoryInterface
{
    public function findById(string $id): ?PosyanduEntity;

    public function save(PosyanduEntity $posyandu): PosyanduEntity;
}
