<?php

declare(strict_types=1);

namespace App\Infrastructure\Authentication\Services;

use App\Application\Features\Authentication\Common\Interfaces\UserVerifiedEventDispatcherInterface;
use App\Domain\Authentication\Exceptions\UserNotFoundException;
use App\Infrastructure\Authentication\Models\User;
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
