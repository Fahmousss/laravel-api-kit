<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Location;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Location\Queries\GetStuntingClusters\GetStuntingClustersQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Resources\ChildResource;
use Illuminate\Http\JsonResponse;

final class StuntingClusterController extends ApiController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}
    public function __invoke(): JsonResponse
    {
        $clusters = $this->queryBus->dispatch(new GetStuntingClustersQuery());

        return $this->success(ChildResource::collection($clusters), 'Stunting clusters retrieved successfully.');
    }
}
