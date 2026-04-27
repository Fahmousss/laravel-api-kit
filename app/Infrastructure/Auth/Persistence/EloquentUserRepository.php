<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Persistence;

use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;
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

    public function findById(string $id): ?UserEntity
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
            $model = User::query()->find($user->id);

            throw_if($model === null, UserNotFoundException::class, (string) $user->id);

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

    public function getAllPaginated(int $page, int $perPage): PaginatedResult
    {
        $paginator = User::query()->paginate(perPage: $perPage, page: $page);

        $entities = array_map(
            fn (User $model): UserEntity => $this->mapToEntity($model, UserEntity::class),
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
