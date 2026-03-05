<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Admin;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary\GetGeoTaggedStuntingSummaryQuery;
use App\Presentation\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;

final class DashboardController extends ApiController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(): JsonResponse
    {
        $result = $this->queryBus->dispatch(new GetGeoTaggedStuntingSummaryQuery());

        return $this->success($result, 'Dashboard summary retrieved successfully');
    }
}
