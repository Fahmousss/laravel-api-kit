<?php

declare(strict_types=1);

namespace App\Infrastructure\Authorization\Providers;

use App\Application\Features\Authorization\Common\Interfaces\ActorContextResolverServiceInterface;
use App\Infrastructure\Authorization\Services\ActorContextResolverService;
use Illuminate\Support\ServiceProvider;

final class AuthorizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ActorContextResolverServiceInterface::class, ActorContextResolverService::class);
    }
}

