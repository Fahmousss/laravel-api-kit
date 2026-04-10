<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\SendPasswordResetLink;

use App\Application\Features\Authentication\Common\Interfaces\PasswordResetServiceInterface;

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
