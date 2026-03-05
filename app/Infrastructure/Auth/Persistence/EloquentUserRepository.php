<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Persistence;

use App\Domain\Auth\Entities\UserEntity;
use App\Domain\Auth\Enums\Role;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\Models\User;
use App\Infrastructure\Shared\Traits\EntityMapper;
use App\Infrastructure\Shared\Traits\RoleMapper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class EloquentUserRepository implements UserRepositoryInterface
{
    use EntityMapper;
    use RoleMapper;

    public function findByEmail(string $email): ?UserEntity
    {
        $model = User::query()->with('roles')->where('email', $email)->first();

        if ($model === null) {
            return null;
        }

        return $this->mapToEntity($model, UserEntity::class);
    }

    public function findById(int $id): ?UserEntity
    {
        $model = User::query()->with('roles')->find($id);

        Log::info($model);

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

    public function markEmailAsVerified(int $userId): void
    {
        $model = User::query()->find($userId);

        if ($model === null) {
            return;
        }

        $model->markEmailAsVerified();
    }

    public function assignRole(int $userId, Role $role): void
    {
        $model = User::query()->find($userId);

        if ($model === null) {
            return;
        }

        // We use DB facade instead of attach() because the related model is a dummy and we're just storing a string enum value.
        $exists = DB::table('role_user')
            ->where('user_id', $userId)
            ->where('role', $role->value)
            ->exists();

        if (! $exists) {
            DB::table('role_user')->insert([
                'user_id'    => $userId,
                'role'       => $role->value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function assignPosyandu(int $userId, int $posyanduId): void
    {
        $model = User::query()->find($userId);

        if ($model === null) {
            return;
        }

        $model->update([
            'posyandu_id' => $posyanduId,
        ]);
    }
}
