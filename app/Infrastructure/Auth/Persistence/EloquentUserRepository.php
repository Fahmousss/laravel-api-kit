<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Persistence;

use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\Models\User;
use App\Infrastructure\Shared\Traits\EntityMapper;

final class EloquentUserRepository implements UserRepositoryInterface
{
    use EntityMapper;

    public function findByEmail(string $email): ?UserEntity
    {
        $model = User::query()->where('email', $email)->first();

        if ($model === null) {
            return null;
        }

        return $this->mapToEntity($model, UserEntity::class);
    }

    public function findById(int $id): ?UserEntity
    {
        $model = User::query()->find($id);

        if ($model === null) {
            return null;
        }

        return $this->mapToEntity($model, UserEntity::class);
    }

    public function save(UserEntity $user): UserEntity
    {
        if ($user->id !== null) {
            $model = User::query()->findOrFail($user->id);
            $model->update([
                'name'     => $user->name,
                'email'    => $user->email,
                'password' => $user->password,
            ]);
        } else {
            $model = User::query()->create([
                'name'     => $user->name,
                'email'    => $user->email,
                'password' => $user->password,
            ]);
        }

        return $this->mapToEntity($model, UserEntity::class);
    }
}
