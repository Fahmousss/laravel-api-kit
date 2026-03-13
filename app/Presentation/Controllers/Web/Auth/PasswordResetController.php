<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Auth\Commands\ResetPassword\ResetPasswordCommand;
use App\Application\Features\Auth\Commands\SendPasswordResetLink\SendPasswordResetLinkCommand;
use App\Presentation\Controllers\Web\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

final class PasswordResetController extends WebController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function create(Request $request): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = $this->commandBus->dispatch(new SendPasswordResetLinkCommand($request->email));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function edit(Request $request, string $token): View
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $status = $this->commandBus->dispatch(new ResetPasswordCommand(
            email: $request->email,
            password: $request->password,
            passwordConfirmation: $request->password_confirmation,
            token: $request->token
        ));

        return $status === Password::PASSWORD_RESET
            ? to_route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
