<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class DashboardController extends WebController
{
    public function __invoke(): View|RedirectResponse
    {
        return view('dashboard');
    }
}
