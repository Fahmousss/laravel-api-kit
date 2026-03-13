<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\LogoutUser;

use App\Application\Features\Auth\Common\Interfaces\SessionServiceInterface;

final readonly class LogoutUserCommandHandler
{
    public function __construct(
        private SessionServiceInterface $sessionService
    ) {}

    public function handle(): void
    {
        $this->sessionService->logout();
        $this->sessionService->refresh();
    }
}
