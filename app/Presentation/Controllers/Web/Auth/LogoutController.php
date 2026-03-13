<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Auth;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Auth\Commands\LogoutUser\LogoutUserCommand;
use App\Presentation\Controllers\Web\WebController;
use Illuminate\Http\RedirectResponse;

final class LogoutController extends WebController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(): RedirectResponse
    {
        $this->commandBus->dispatch(new LogoutUserCommand());

        return to_route('login');
    }
}
