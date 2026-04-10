<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\LogoutUser;

use App\Application\Features\Authentication\Common\Interfaces\AuthTokenServiceInterface;

final readonly class LogoutUserCommandHandler
{
    public function __construct(
        private AuthTokenServiceInterface $tokenService
    ) {}

    public function handle(LogoutUserCommand $command): void
    {
        $this->tokenService->revokeToken($command->token);
    }
}
