<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Admin;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Children\Commands\CreateChild\CreateChildCommand;
use App\Application\Features\Children\Queries\GetChildrenByPosyandu\GetChildrenByPosyanduQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Resources\ChildResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ChildController extends ApiController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function getByPosyandu(int $posyanduId): JsonResponse
    {
        $result = $this->queryBus->dispatch(new GetChildrenByPosyanduQuery(posyanduId: $posyanduId));

        return $this->success(ChildResource::collection($result), 'Children retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'posyandu_id' => ['required', 'integer', 'exists:posyandus,id'],
            'nik'         => ['required', 'string', 'max:16', 'unique:children,nik'],
            'name'        => ['required', 'string', 'max:255'],
            'dob'         => ['required', 'date'],
            'gender'      => ['required', 'in:L,P'],
            'parent_name' => ['required', 'string', 'max:255'],
        ]);

        $result = $this->commandBus->dispatch(new CreateChildCommand(
            posyanduId: (int) $validated['posyandu_id'],
            nik: $validated['nik'],
            name: $validated['name'],
            dob: $validated['dob'],
            gender: $validated['gender'],
            parentName: $validated['parent_name'],
        ));

        return $this->created(new ChildResource($result), 'Child created successfully');
    }
}
