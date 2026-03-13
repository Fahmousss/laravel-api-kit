<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Providers;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\Commands\LoginUser\LoginUserCommand;
use App\Application\Features\Auth\Commands\LoginUser\LoginUserCommandHandler;
use App\Application\Features\Auth\Commands\LogoutUser\LogoutUserCommand;
use App\Application\Features\Auth\Commands\LogoutUser\LogoutUserCommandHandler;
use App\Application\Features\Auth\Commands\RegisterUser\RegisterUserCommand;
use App\Application\Features\Auth\Commands\RegisterUser\RegisterUserCommandHandler;
use App\Application\Features\Auth\Commands\ResendVerificationEmail\ResendVerificationEmailCommand;
use App\Application\Features\Auth\Commands\ResendVerificationEmail\ResendVerificationEmailCommandHandler;
use App\Application\Features\Auth\Commands\ResetPassword\ResetPasswordCommand;
use App\Application\Features\Auth\Commands\ResetPassword\ResetPasswordCommandHandler;
use App\Application\Features\Auth\Commands\SendPasswordResetLink\SendPasswordResetLinkCommand;
use App\Application\Features\Auth\Commands\SendPasswordResetLink\SendPasswordResetLinkCommandHandler;
use App\Application\Features\Auth\Commands\VerifyEmail\VerifyEmailCommand;
use App\Application\Features\Auth\Commands\VerifyEmail\VerifyEmailCommandHandler;
use App\Application\Features\Auth\Common\Interfaces\PasswordResetServiceInterface;
use App\Application\Features\Auth\Common\Interfaces\SessionServiceInterface;
use App\Application\Features\Auth\Common\Interfaces\UserVerifiedEventDispatcherInterface;
use App\Application\Features\Auth\Common\Interfaces\VerifyEmailNotificationServiceInterface;
use App\Application\Features\Auth\Queries\GetAllUsers\GetAllUsersQuery;
use App\Application\Features\Auth\Queries\GetAllUsers\GetAllUsersQueryHandler;
use App\Application\Features\Auth\Queries\GetAuthenticatedUser\GetAuthenticatedUserQuery;
use App\Application\Features\Auth\Queries\GetAuthenticatedUser\GetAuthenticatedUserQueryHandler;
use App\Application\Features\Auth\Queries\GetUserById\GetUserByIdQuery;
use App\Application\Features\Auth\Queries\GetUserById\GetUserByIdQueryHandler;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\Persistence\EloquentUserRepository;
use App\Infrastructure\Auth\Services\PasswordResetService;
use App\Infrastructure\Auth\Services\SessionService;
use App\Infrastructure\Auth\Services\UserVerifiedDispatcherService;
use App\Infrastructure\Auth\Services\VerifyEmailNotificationService;
use Illuminate\Support\ServiceProvider;

final class AuthenticationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

        // Bind application services
        $this->app->bind(VerifyEmailNotificationServiceInterface::class, VerifyEmailNotificationService::class);
        $this->app->bind(PasswordResetServiceInterface::class, PasswordResetService::class);
        $this->app->bind(UserVerifiedEventDispatcherInterface::class, UserVerifiedDispatcherService::class);
        $this->app->bind(SessionServiceInterface::class, SessionService::class);
    }

    public function boot(CommandBusInterface $commandBus, QueryBusInterface $queryBus): void
    {
        $commandBus->register(ResetPasswordCommand::class, ResetPasswordCommandHandler::class);
        $commandBus->register(SendPasswordResetLinkCommand::class, SendPasswordResetLinkCommandHandler::class);
        $commandBus->register(ResendVerificationEmailCommand::class, ResendVerificationEmailCommandHandler::class);
        $commandBus->register(VerifyEmailCommand::class, VerifyEmailCommandHandler::class);
        $commandBus->register(LogoutUserCommand::class, LogoutUserCommandHandler::class);
        $commandBus->register(LoginUserCommand::class, LoginUserCommandHandler::class);

        $queryBus->register(GetUserByIdQuery::class, GetUserByIdQueryHandler::class);
        $queryBus->register(GetAllUsersQuery::class, GetAllUsersQueryHandler::class);
        $queryBus->register(GetAuthenticatedUserQuery::class, GetAuthenticatedUserQueryHandler::class);

        $commandBus->register(RegisterUserCommand::class, RegisterUserCommandHandler::class);
    }
}
