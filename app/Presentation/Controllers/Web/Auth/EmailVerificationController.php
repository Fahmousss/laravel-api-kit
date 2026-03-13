<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\Commands\ResendVerificationEmail\ResendVerificationEmailCommand;
use App\Application\Features\Auth\Commands\VerifyEmail\VerifyEmailCommand;
use App\Application\Features\Auth\DTOs\UserDTO;
use App\Presentation\Controllers\Web\WebController;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class EmailVerificationController extends WebController
{
    use HasAuthenticatedUser;

    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function show(): View|RedirectResponse
    {
        $user = $this->getAuthenticatedUser();

        return $user?->emailVerifiedAt !== null
            ? to_route('dashboard')
            : view('auth.verify-email');
    }

    public function verify(): RedirectResponse
    {
        $user = $this->getAuthenticatedUser();

        if ($user?->emailVerifiedAt !== null) {
            return to_route('dashboard');
        }

        if (! $user instanceof UserDTO) {
            return to_route('login');
        }

        $this->commandBus->dispatch(new VerifyEmailCommand($user->id));

        return to_route('dashboard')->with('verified', true);
    }

    public function resend(): RedirectResponse
    {
        $user = $this->getAuthenticatedUser();

        if ($user?->emailVerifiedAt !== null) {
            return to_route('dashboard');
        }

        if (! $user instanceof UserDTO) {
            return to_route('login');
        }

        $this->commandBus->dispatch(new ResendVerificationEmailCommand($user->email));

        return back()->with('status', 'verification-link-sent');
    }
}
