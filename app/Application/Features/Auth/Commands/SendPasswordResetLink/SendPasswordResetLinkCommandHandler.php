<?php

declare(strict_types=1);

namespace App\Application\Features\Auth\Commands\SendPasswordResetLink;

use App\Application\Features\Auth\Common\Interfaces\PasswordResetServiceInterface;

final readonly class SendPasswordResetLinkCommandHandler
{
    public function __construct(
        private PasswordResetServiceInterface $passwordResetService
    ) {}

    public function handle(SendPasswordResetLinkCommand $command): string
    {
        return $this->passwordResetService->sendResetLink($command->email);
    }
}
