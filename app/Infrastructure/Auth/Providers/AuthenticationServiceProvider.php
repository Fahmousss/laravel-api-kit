<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Providers;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\Commands\LogoutUser\LogoutUserCommand;
use App\Application\Features\Auth\Commands\LogoutUser\LogoutUserCommandHandler;
use App\Application\Features\Auth\Common\Interfaces\AuthenticatedUserContextInterface;
use App\Application\Features\Auth\Common\Interfaces\AuthTokenServiceInterface;
use App\Application\Features\Auth\Queries\GetAllUsers\GetAllUsersQuery;
use App\Application\Features\Auth\Queries\GetAllUsers\GetAllUsersQueryHandler;
use App\Application\Features\Auth\Queries\GetAuthToken\GetAuthTokenQuery;
use App\Application\Features\Auth\Queries\GetAuthToken\GetAuthTokenQueryHandler;
use App\Application\Features\Auth\Queries\GetAuthUserId\GetAuthUserIdQuery;
use App\Application\Features\Auth\Queries\GetAuthUserId\GetAuthUserIdQueryHandler;
use App\Application\Features\Auth\Queries\GetUserById\GetUserByIdQuery;
use App\Application\Features\Auth\Queries\GetUserById\GetUserByIdQueryHandler;
use App\Application\Features\Auth\Queries\LoginUser\LoginUserQuery;
use App\Application\Features\Auth\Queries\LoginUser\LoginUserQueryHandler;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\Persistence\EloquentUserRepository;
use App\Infrastructure\Auth\Services\AuthenticatedUserContext;
use App\Infrastructure\Auth\Services\JwtTokenService;
use Illuminate\Support\ServiceProvider;

final class AuthenticationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

        // Bind application services
        $this->app->bind(AuthTokenServiceInterface::class, JwtTokenService::class);
        $this->app->bind(AuthenticatedUserContextInterface::class, AuthenticatedUserContext::class);

    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(LogoutUserCommand::class, LogoutUserCommandHandler::class);

        $queryBus->register(GetUserByIdQuery::class, GetUserByIdQueryHandler::class);
        $queryBus->register(GetAllUsersQuery::class, GetAllUsersQueryHandler::class);

        $queryBus->register(LoginUserQuery::class, LoginUserQueryHandler::class);

        $queryBus->register(GetAuthUserIdQuery::class, GetAuthUserIdQueryHandler::class);
        $queryBus->register(GetAuthTokenQuery::class, GetAuthTokenQueryHandler::class);
    }
}
