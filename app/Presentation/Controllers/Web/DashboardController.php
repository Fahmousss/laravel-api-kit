<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\DTOs\UserDTO;
use App\Application\Features\Auth\Queries\GetUserById\GetUserByIdQuery;
use App\Domain\Auth\Enums\Role;
use App\Presentation\ViewModels\Auth\UserViewModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class DashboardController extends WebController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(): View|RedirectResponse
    {
        /** @var null|UserDTO $dto */
        $dto = $this->queryBus->dispatch(new GetUserByIdQuery((int) Auth::id()));

        if ($dto === null) {
            Auth::logout();

            return to_route('web.login');
        }

        if (in_array(Role::Admin, $dto->roles, true)) {
            return to_route('admin.dashboard');
        }

        if (in_array(Role::Kader, $dto->roles, true)) {
            return to_route('kader.dashboard');
        }

        return view('dashboard', ['user' => new UserViewModel($dto)]);
    }
}
