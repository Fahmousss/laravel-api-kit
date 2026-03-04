<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Child;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Child\Commands\LogMeasurement\LogMeasurementCommand;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\LogMeasurementRequest;
use App\Presentation\Resources\MeasurementResource;
use Illuminate\Http\JsonResponse;

final class LogMeasurementController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(LogMeasurementRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $command = new LogMeasurementCommand(
            childId: $validated['child_id'],
            heightCm: (float) $validated['height_cm'],
            weightKg: (float) $validated['weight_kg'],
            measuredAt: $validated['measured_at'],
        );

        $measurement = $this->commandBus->dispatch($command);

        return $this->success(new MeasurementResource($measurement), 'Measurement logged successfully.');
    }
}
