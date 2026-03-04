<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Child;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Child\Commands\RegisterChild\RegisterChildCommand;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\RegisterChildRequest;
use App\Presentation\Resources\ChildResource;
use Illuminate\Http\JsonResponse;

final class RegisterChildController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(RegisterChildRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // RBAC constraint: Cadre can only register to their assigned posyandu
        if ($request->user()->hasRole('cadre') && $request->user()->posyandu_id !== $validated['posyandu_id']) {
            abort(403, 'Unauthorized to register child in this Posyandu.');
        }

        $command = new RegisterChildCommand(
            posyanduId: $validated['posyandu_id'],
            name: $validated['name'],
            nik: $validated['nik'] ?? null,
            dateOfBirth: $validated['date_of_birth'],
            gender: $validated['gender'],
        );

        $child = $this->commandBus->dispatch($command);

        return $this->success(new ChildResource($child), 'Child registered successfully.');
    }
}
