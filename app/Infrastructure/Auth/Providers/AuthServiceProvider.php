<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Providers;

use App\Application\Auth\Commands\RegisterUserCommand;
use App\Application\Auth\Commands\RegisterUserCommandHandler;
use App\Application\Auth\Queries\LoginUserQuery;
use App\Application\Auth\Queries\LoginUserQueryHandler;
use App\Application\Bus\CommandBus;
use App\Application\Bus\QueryBus;
use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\Persistence\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

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
