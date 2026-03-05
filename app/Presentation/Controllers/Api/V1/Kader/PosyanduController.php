<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Kader;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Posyandus\Commands\CreatePosyandu\CreatePosyanduCommand;
use App\Application\Features\Posyandus\Queries\GetPosyandus\GetPosyandusQuery;
use App\Presentation\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class PosyanduController extends ApiController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page    = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 15);

        $result = $this->queryBus->dispatch(new GetPosyandusQuery(page: $page, perPage: $perPage));

        return $this->paginated($result, \App\Presentation\Resources\PosyanduResource::class, 'Posyandus retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'lat'      => ['nullable', 'numeric'],
            'lng'      => ['nullable', 'numeric'],
        ]);

        $result = $this->commandBus->dispatch(new CreatePosyanduCommand(
            name: $validated['name'],
            district: $validated['district'],
            location: $validated['location'] ?? null,
            lat: isset($validated['lat']) ? (float) $validated['lat'] : null,
            lng: isset($validated['lng']) ? (float) $validated['lng'] : null,
        ));

        return $this->created(new \App\Presentation\Resources\PosyanduResource($result), 'Posyandu created successfully');
    }
}
