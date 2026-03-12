<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Auth;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\DTOs\UserDTO;
use App\Application\Features\Auth\Queries\LoginUser\LoginUserQuery;
use App\Domain\Auth\Enums\Role;
use App\Presentation\Controllers\Web\WebController;
use App\Presentation\Requests\Api\V1\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class LoginController extends WebController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function show(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        /** @var null|UserDTO $result */
        $result = $this->queryBus->dispatch(new LoginUserQuery(
            email: $request->email,
            password: $request->password,
        ));

        if ($result === null) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        Auth::loginUsingId($result->id);

        $request->session()->regenerate();

        $intendedUrl = match (true) {
            in_array(Role::Stakeholder, $result->roles, true) => route('admin.dashboard'),
            in_array(Role::Admin, $result->roles, true) => route('admin.dashboard'),
            in_array(Role::Kader, $result->roles, true) => route('kader.dashboard'),
            default                                     => route('web.dashboard'),
        };
        

        return redirect()->intended($intendedUrl);
    }
}
