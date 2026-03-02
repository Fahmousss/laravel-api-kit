<?php

declare(strict_types=1);

namespace App\Domain\Auth\Repositories;

use App\Domain\Auth\Entities\UserEntity;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserEntity;

    public function findById(int $id): ?UserEntity;

    public function save(UserEntity $user): UserEntity;
}
