<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Auth\Commands\LoginUser\LoginUserCommand;
use App\Application\Features\Auth\DTOs\UserDTO;
use App\Presentation\Controllers\Web\WebController;
use App\Presentation\Requests\Auth\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class LoginController extends WebController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        /** @var null|UserDTO $dto */
        $dto = $this->commandBus->dispatch(new LoginUserCommand(
            email: $request->validated('email'),
            password: $request->validated('password'),
            remember: $request->boolean('remember'),
        ));

        if ($dto === null) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        return redirect()->intended(route('dashboard'));
    }
}
