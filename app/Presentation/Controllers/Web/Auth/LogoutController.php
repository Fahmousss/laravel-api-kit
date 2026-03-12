<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Auth;

use App\Presentation\Controllers\Web\WebController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LogoutController extends WebController
{
    public function __invoke(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('web.login');
    }
}
