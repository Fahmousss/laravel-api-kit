<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\ResetPassword;

use App\Application\Features\Authentication\Common\Interfaces\PasswordResetServiceInterface;

final readonly class ResetPasswordCommandHandler
{
    public function __construct(
        private PasswordResetServiceInterface $passwordResetService
    ) {}

    public function handle(ResetPasswordCommand $command): string
    {
        return $this->passwordResetService->resetPassword(
            $command->email,
            $command->password,
            $command->passwordConfirmation,
            $command->token
        );
    }
}
