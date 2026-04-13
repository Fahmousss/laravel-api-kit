<?php

declare(strict_types=1);

namespace App\Application\Features\Authentication\Commands\ResetPassword;

use App\Application\Features\Authentication\Common\Interfaces\PasswordResetServiceInterface;
use App\Application\Features\Authentication\DTOs\PasswordResetStatusDTO;

final readonly class ResetPasswordCommandHandler
{
    public function __construct(
        private PasswordResetServiceInterface $passwordResetService
    ) {}

    public function handle(ResetPasswordCommand $command): PasswordResetStatusDTO
    {
        return $this->passwordResetService->resetPassword(
            $command->email,
            $command->password,
            $command->passwordConfirmation,
            $command->token
        );
    }
}

