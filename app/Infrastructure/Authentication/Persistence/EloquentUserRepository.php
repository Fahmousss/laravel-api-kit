<?php

declare(strict_types=1);

namespace App\Infrastructure\Authentication\Persistence;

use App\Domain\Authentication\Entities\UserEntity;
use App\Domain\Authentication\Exceptions\UserNotFoundException;
use App\Domain\Authentication\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Pagination\PaginatedResult;
use App\Infrastructure\Authentication\Models\User;
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

    public function markEmailAsVerified(string $userId): void
    {
        $model = User::query()->find($userId);

        if ($model === null) {
            return;
        }

        $model->markEmailAsVerified();
    }

    public function paginate(array $filters, int $page, int $perPage): PaginatedResult
    {
        $paginator = User::query()->orderByDesc('created_at')->paginate(
            perPage: $perPage,
            columns: ['*'],
            pageName: 'page',
            page: $page);

        $entities = array_map(
            fn (User $model): array|object => $this->mapToEntity($model, UserEntity::class),
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

    public function delete(string $id): void
    {
        User::destroy($id);
    }
}
