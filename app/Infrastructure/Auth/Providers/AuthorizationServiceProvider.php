<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Providers;

use App\Domain\Auth\Enums\UserPermission;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Contracts\DomainPermission;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Map Laravel Gate checks to our Domain Permission Enums
        Gate::before(function ($user, $ability) {

            // Register all domain-specific permission enums here
            $permissionEnums = [
                UserPermission::class,
            ];

            // Find if the requested ability exists in any of them
            foreach ($permissionEnums as $enumClass) {

                /** @var null|DomainPermission $permission */
                $permission = $enumClass::tryFrom($ability);

                if ($permission !== null) {

                    // To keep DDD pure, we need to use the UserEntity instead of the Eloquent Model.
                    /** @var UserRepositoryInterface $userRepo */
                    $userRepo = App::make(UserRepositoryInterface::class);

                    /** @var UserEntity $entity */
                    $entity = $userRepo->findById($user->id);

                    if ($entity !== null) {
                        return $entity->hasPermission($permission);
                    }
                }
            }

            // Return null to let other gates/policies run if this ability isn't a known enum
            return null;
        });
    }
}
