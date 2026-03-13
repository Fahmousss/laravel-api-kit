<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Auth\Commands\RegisterUser\RegisterUserCommand;
use App\Presentation\Controllers\Web\WebController;
use App\Presentation\Requests\Auth\RegisterRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class RegistrationController extends WebController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $this->commandBus->dispatch(new RegisterUserCommand(
            name: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
        ));

        return to_route('dashboard')->with('success', 'Registration successful! Please verify your email.');
    }
}
