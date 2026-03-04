<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Application\Features\Auth\Common\Interfaces\UserVerifiedEventDispatcherInterface;
use App\Domain\Auth\Exceptions\UserNotFoundException;
use App\Infrastructure\Auth\Models\User;
use Illuminate\Auth\Events\Verified;

final class UserVerifiedDispatcherService implements UserVerifiedEventDispatcherInterface
{
    public function dispatch(int $userId): void
    {
        $model = User::query()->find($userId);

        throw_if($model === null, UserNotFoundException::class, (string) $userId);

        event(new Verified($model));
    }
}
