<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Kader;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Children\Commands\CreateChild\CreateChildCommand;
use App\Application\Features\Children\Queries\GetChildrenByPosyandu\GetChildrenByPosyanduQuery;
use App\Application\Features\Posyandus\Queries\GetPosyandusByUserId\GetPosyandusByUserIdQuery;
use App\Presentation\ViewModels\Web\MasterData\ChildListViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final readonly class ChildController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
    ) {}

    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        // Get assigned Posyandus for the dropdown
        $posyandusResult = $this->queryBus->dispatch(new GetPosyandusByUserIdQuery(
            userId: $userId,
            page: 1, // Kaders rarely have >10 Posyandus, but ideally we fetch all. For simplicity, we use page 1.
            perPage: 100
        ));

        $posyanduOptions = [];
        foreach ($posyandusResult->items as $posyandu) {
            $posyanduOptions[$posyandu->id] = $posyandu->name;
        }

        // Determine which Posyandu's children to show
        $selectedPosyanduId = $request->query('posyandu_id');
        if (! $selectedPosyanduId && $posyanduOptions !== []) {
            $selectedPosyanduId = array_key_first($posyanduOptions);
        }

        $viewModel = null;
        if ($selectedPosyanduId) {
            $childrenResult = $this->queryBus->dispatch(new GetChildrenByPosyanduQuery(
                posyanduId: (int) $selectedPosyanduId
            ));

            $viewModel = new ChildListViewModel($childrenResult);
        }

        return view('kader.children.index', [
            'posyanduOptions'    => $posyanduOptions,
            'selectedPosyanduId' => $selectedPosyanduId ? (int) $selectedPosyanduId : null,
            'viewModel'          => $viewModel,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'posyandu_id' => ['required', 'integer', 'exists:posyandus,id'],
            'nik'         => ['required', 'string', 'max:16', 'unique:children,nik'],
            'name'        => ['required', 'string', 'max:255'],
            'dob'         => ['required', 'date'],
            'gender'      => ['required', 'in:L,P'],
            'parent_name' => ['required', 'string', 'max:255'],
        ]);

        $this->commandBus->dispatch(new CreateChildCommand(
            posyanduId: (int) $validated['posyandu_id'],
            nik: $validated['nik'],
            name: $validated['name'],
            dob: $validated['dob'],
            gender: $validated['gender'],
            parentName: $validated['parent_name'],
        ));

        return to_route('kader.children.index', ['posyandu_id' => $validated['posyandu_id']])
            ->with('success', 'Child registered successfully.');
    }
}
