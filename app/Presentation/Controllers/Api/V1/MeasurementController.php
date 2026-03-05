<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Measurements\Commands\CreateMeasurement\CreateMeasurementCommand;
use App\Presentation\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

final class MeasurementController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'child_id'         => ['required', 'integer', 'exists:children,id'],
            'measurement_date' => ['required', 'date'],
            'height_cm'        => ['required', 'numeric', 'min:10', 'max:250'],
            'weight_kg'        => ['nullable', 'numeric', 'min:0.5', 'max:150'],
            'lat'              => ['nullable', 'numeric', 'between:-90,90'],
            'lng'              => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $result = $this->commandBus->dispatch(new CreateMeasurementCommand(
            childId: (int) $validated['child_id'],
            measurementDate: Carbon::parse($validated['measurement_date']),
            heightCm: (float) $validated['height_cm'],
            weightKg: isset($validated['weight_kg']) ? (float) $validated['weight_kg'] : null,
            lat: isset($validated['lat']) ? (float) $validated['lat'] : null,
            lng: isset($validated['lng']) ? (float) $validated['lng'] : null,
        ));

        return $this->created($result, 'Measurement created successfully');
    }
}
