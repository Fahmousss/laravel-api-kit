<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Admin;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Auth\Commands\AssignPosyandu\AssignPosyanduCommand;
use App\Application\Features\Auth\Commands\AssignRole\AssignRoleCommand;
use App\Application\Features\Auth\Commands\RevokeRole\RevokeRoleCommand;
use App\Application\Features\Auth\Queries\GetAllUsers\GetAllUsersQuery;
use App\Application\Features\Posyandus\Queries\GetPosyandus\GetPosyandusQuery;
use App\Domain\Auth\Enums\Role;
use App\Presentation\ViewModels\Web\MasterData\UserListViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final readonly class UserController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
    ) {}

    public function index(Request $request): View
    {
        $page    = (int) $request->query('page', 1);
        $perPage = 10;

        $result    = $this->queryBus->dispatch(new GetAllUsersQuery(page: $page, perPage: $perPage));
        $viewModel = new UserListViewModel($result);

        // Fetch POSYANDU list for the assignment dropdown (assume 100 is enough for simple dropdown)
        $posyandusResult = $this->queryBus->dispatch(new GetPosyandusQuery(page: 1, perPage: 100));
        $posyanduOptions = [];
        foreach ($posyandusResult->items as $p) {
            $posyanduOptions[$p->id] = $p->name.' - '.$p->district;
        }

        return view('admin.users.index', [
            'viewModel'       => $viewModel,
            'posyanduOptions' => $posyanduOptions,
        ]);
    }

    public function assignRole(Request $request, int $id): RedirectResponse
    {
        // Actually, we'll check if AssignRoleCommand takes an enum or string. I'll pass a string.
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:admin,kader,parent'],
        ]);

        $this->commandBus->dispatch(new AssignRoleCommand(
            userId: $id,
            role: Role::from($validated['role']),
        ));

        return to_route('admin.users.index')->with('success', 'User role assigned successfully.');
    }

    public function revokeRole(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:admin,kader,parent'],
        ]);

        $this->commandBus->dispatch(new RevokeRoleCommand(
            userId: $id,
            role: Role::from($validated['role']),
        ));

        return to_route('admin.users.index')->with('success', 'User role revoked successfully.');
    }

    public function assignPosyandu(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'posyandu_id' => ['required', 'integer', 'exists:posyandus,id'],
        ]);

        $this->commandBus->dispatch(new AssignPosyanduCommand(
            userId: $id,
            posyanduId: (int) $validated['posyandu_id'],
        ));

        return to_route('admin.users.index')->with('success', 'Posyandu assigned to user successfully.');
    }
}
