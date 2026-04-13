<?php

declare(strict_types=1);

namespace App\Infrastructure\Authentication\Providers;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Authentication\Commands\LogoutUser\LogoutUserCommand;
use App\Application\Features\Authentication\Commands\LogoutUser\LogoutUserCommandHandler;
use App\Application\Features\Authentication\Commands\RegisterUser\RegisterUserCommand;
use App\Application\Features\Authentication\Commands\RegisterUser\RegisterUserCommandHandler;
use App\Application\Features\Authentication\Commands\ResendVerificationEmail\ResendVerificationEmailCommand;
use App\Application\Features\Authentication\Commands\ResendVerificationEmail\ResendVerificationEmailCommandHandler;
use App\Application\Features\Authentication\Commands\ResetPassword\ResetPasswordCommand;
use App\Application\Features\Authentication\Commands\ResetPassword\ResetPasswordCommandHandler;
use App\Application\Features\Authentication\Commands\SendPasswordResetLink\SendPasswordResetLinkCommand;
use App\Application\Features\Authentication\Commands\SendPasswordResetLink\SendPasswordResetLinkCommandHandler;
use App\Application\Features\Authentication\Commands\VerifyEmail\VerifyEmailCommand;
use App\Application\Features\Authentication\Commands\VerifyEmail\VerifyEmailCommandHandler;
use App\Application\Features\Authentication\Common\Interfaces\AuthenticatedUserContextInterface;
use App\Application\Features\Authentication\Common\Interfaces\AuthTokenServiceInterface;
use App\Application\Features\Authentication\Common\Interfaces\PasswordResetServiceInterface;
use App\Application\Features\Authentication\Common\Interfaces\UserVerifiedEventDispatcherInterface;
use App\Application\Features\Authentication\Common\Interfaces\VerifyEmailNotificationServiceInterface;
use App\Application\Features\Authentication\Queries\CheckEmailVerified\CheckEmailVerifiedQuery;
use App\Application\Features\Authentication\Queries\CheckEmailVerified\CheckEmailVerifiedQueryHandler;
use App\Application\Features\Authentication\Queries\GetAllUsers\GetAllUsersQuery;
use App\Application\Features\Authentication\Queries\GetAllUsers\GetAllUsersQueryHandler;
use App\Application\Features\Authentication\Queries\GetAuthToken\GetAuthTokenQuery;
use App\Application\Features\Authentication\Queries\GetAuthToken\GetAuthTokenQueryHandler;
use App\Application\Features\Authentication\Queries\GetAuthUserId\GetAuthUserIdQuery;
use App\Application\Features\Authentication\Queries\GetAuthUserId\GetAuthUserIdQueryHandler;
use App\Application\Features\Authentication\Queries\GetCurrentUser\GetCurrentUserQuery;
use App\Application\Features\Authentication\Queries\GetCurrentUser\GetCurrentUserQueryHandler;
use App\Application\Features\Authentication\Queries\GetUserById\GetUserByIdQuery;
use App\Application\Features\Authentication\Queries\GetUserById\GetUserByIdQueryHandler;
use App\Application\Features\Authentication\Queries\LoginUser\LoginUserQuery;
use App\Application\Features\Authentication\Queries\LoginUser\LoginUserQueryHandler;
use App\Domain\Authentication\Repositories\UserRepositoryInterface;
use App\Infrastructure\Authentication\Persistence\EloquentUserRepository;
use App\Infrastructure\Authentication\Services\AuthenticatedUserContext;
use App\Infrastructure\Authentication\Services\PasswordResetService;
use App\Infrastructure\Authentication\Services\SanctumTokenService;
use App\Infrastructure\Authentication\Services\UserVerifiedDispatcherService;
use App\Infrastructure\Authentication\Services\VerifyEmailNotificationService;
use Illuminate\Support\ServiceProvider;

final class AuthenticationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

        // Bind application services
        $this->app->bind(AuthTokenServiceInterface::class, SanctumTokenService::class);
        $this->app->bind(VerifyEmailNotificationServiceInterface::class, VerifyEmailNotificationService::class);
        $this->app->bind(PasswordResetServiceInterface::class, PasswordResetService::class);
        $this->app->bind(UserVerifiedEventDispatcherInterface::class, UserVerifiedDispatcherService::class);
        $this->app->bind(AuthenticatedUserContextInterface::class, AuthenticatedUserContext::class);

    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(ResetPasswordCommand::class, ResetPasswordCommandHandler::class);
        $commandBus->register(SendPasswordResetLinkCommand::class, SendPasswordResetLinkCommandHandler::class);
        $commandBus->register(ResendVerificationEmailCommand::class, ResendVerificationEmailCommandHandler::class);
        $commandBus->register(VerifyEmailCommand::class, VerifyEmailCommandHandler::class);
        $commandBus->register(LogoutUserCommand::class, LogoutUserCommandHandler::class);

        $queryBus->register(GetUserByIdQuery::class, GetUserByIdQueryHandler::class);
        $queryBus->register(GetAllUsersQuery::class, GetAllUsersQueryHandler::class);

        $commandBus->register(RegisterUserCommand::class, RegisterUserCommandHandler::class);
        $queryBus->register(LoginUserQuery::class, LoginUserQueryHandler::class);

        $queryBus->register(GetAuthUserIdQuery::class, GetAuthUserIdQueryHandler::class);
        $queryBus->register(GetAuthTokenQuery::class, GetAuthTokenQueryHandler::class);
        $queryBus->register(CheckEmailVerifiedQuery::class, CheckEmailVerifiedQueryHandler::class);
        $queryBus->register(GetCurrentUserQuery::class, GetCurrentUserQueryHandler::class);
    }
}
