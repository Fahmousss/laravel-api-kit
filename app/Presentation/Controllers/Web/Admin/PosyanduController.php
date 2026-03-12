<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Admin;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Posyandus\Commands\CreatePosyandu\CreatePosyanduCommand;
use App\Application\Features\Posyandus\Queries\GetPosyandus\GetPosyandusQuery;
use App\Presentation\ViewModels\Web\MasterData\PosyanduListViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final readonly class PosyanduController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
    ) {}

    public function index(Request $request): View
    {
        $page    = (int) $request->query('page', 1);
        $perPage = 10; // Fixed for web UI

        $result = $this->queryBus->dispatch(new GetPosyandusQuery(page: $page, perPage: $perPage));

        $viewModel = new PosyanduListViewModel($result);

        return view('admin.posyandus.index', [
            'viewModel' => $viewModel,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'lat'      => ['nullable', 'numeric'],
            'lng'      => ['nullable', 'numeric'],
        ]);

        $this->commandBus->dispatch(new CreatePosyanduCommand(
            name: $validated['name'],
            district: $validated['district'],
            location: $validated['location'] ?? null,
            lat: isset($validated['lat']) ? (float) $validated['lat'] : null,
            lng: isset($validated['lng']) ? (float) $validated['lng'] : null,
        ));

        return to_route('admin.posyandus.index')->with('success', 'Posyandu created successfully.');
    }
}
