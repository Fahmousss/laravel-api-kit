<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Providers;

use App\Application\Auth\Common\Interfaces\AuthTokenServiceInterface;
use App\Application\Auth\Common\Interfaces\VerifyEmailNotificationServiceInterface;
use App\Application\Bus\CommandBus;
use App\Application\Bus\QueryBus;
use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\Commands\RegisterUser\RegisterUserCommand;
use App\Application\Features\Auth\Commands\RegisterUser\RegisterUserCommandHandler;
use App\Application\Features\Auth\Queries\LoginUser\LoginUserQuery;
use App\Application\Features\Auth\Queries\LoginUser\LoginUserQueryHandler;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\Persistence\EloquentUserRepository;
use App\Infrastructure\Auth\Services\SanctumTokenService;
use App\Infrastructure\Auth\Services\VerifyEmailNotificationService;
use Illuminate\Support\ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

        // Bind application services
        $this->app->bind(AuthTokenServiceInterface::class, SanctumTokenService::class);
        $this->app->bind(VerifyEmailNotificationServiceInterface::class, VerifyEmailNotificationService::class);

        // Bind buses as singletons
        $this->app->singleton(function (): CommandBusInterface {
            $bus = new CommandBus($this->app);
            $bus->register(RegisterUserCommand::class, RegisterUserCommandHandler::class);

            return $bus;
        });

        $this->app->singleton(function (): QueryBusInterface {
            $bus = new QueryBus($this->app);
            $bus->register(LoginUserQuery::class, LoginUserQueryHandler::class);

            return $bus;
        });
    }
}
