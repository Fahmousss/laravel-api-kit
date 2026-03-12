<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Kader;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Children\Queries\GetChildrenByPosyandu\GetChildrenByPosyanduQuery;
use App\Application\Features\Measurements\Commands\CreateMeasurement\CreateMeasurementCommand;
use App\Application\Features\Posyandus\Queries\GetPosyandusByUserId\GetPosyandusByUserIdQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;

final readonly class MeasurementController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
    ) {}

    public function create(Request $request): View
    {
        $userId = $request->user()->id;

        // Fetch Kader's posyandus
        $posyandusResult = $this->queryBus->dispatch(new GetPosyandusByUserIdQuery(
            userId: $userId,
            page: 1,
            perPage: 100
        ));

        $posyanduOptions = [];
        foreach ($posyandusResult->items as $posyandu) {
            $posyanduOptions[$posyandu->id] = $posyandu->name;
        }

        $selectedPosyanduId = $request->query('posyandu_id');
        if (! $selectedPosyanduId && $posyanduOptions !== []) {
            $selectedPosyanduId = array_key_first($posyanduOptions);
        }

        $childOptions = [];
        if ($selectedPosyanduId) {
            $childrenResult = $this->queryBus->dispatch(new GetChildrenByPosyanduQuery(
                posyanduId: (int) $selectedPosyanduId
            ));

            foreach ($childrenResult as $child) {
                $childOptions[$child->id] = $child->name.' ('.$child->nik.')';
            }
        }

        return view('kader.measurements.create', [
            'posyanduOptions'    => $posyanduOptions,
            'selectedPosyanduId' => $selectedPosyanduId ? (int) $selectedPosyanduId : null,
            'childOptions'       => $childOptions,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'posyandu_id'      => ['required', 'integer', 'exists:posyandus,id'],
            'child_id'         => ['required', 'integer', 'exists:children,id'],
            'measurement_date' => ['required', 'date'],
            'height_cm'        => ['required', 'numeric', 'min:10', 'max:250'],
            'position'         => ['required', 'string', 'in:telentang,berdiri'],
            'weight_kg'        => ['nullable', 'numeric', 'min:0.5', 'max:150'],
            'lat'              => ['nullable', 'numeric', 'between:-90,90'],
            'lng'              => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $this->commandBus->dispatch(new CreateMeasurementCommand(
            childId: (int) $validated['child_id'],
            measurementDate: Date::parse($validated['measurement_date']),
            heightCm: (float) $validated['height_cm'],
            position: $validated['position'],
            weightKg: isset($validated['weight_kg']) ? (float) $validated['weight_kg'] : null,
            lat: isset($validated['lat']) ? (float) $validated['lat'] : null,
            lng: isset($validated['lng']) ? (float) $validated['lng'] : null,
        ));

        return to_route('kader.measurements.create', ['posyandu_id' => $validated['posyandu_id']])
            ->with('success', 'Measurement recorded successfully. Status calculated automatically.');
    }
}
