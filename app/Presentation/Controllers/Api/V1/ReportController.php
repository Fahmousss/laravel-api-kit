<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Reports\Queries\GetMonthlyStuntingReport\GetMonthlyStuntingReportQuery;
use App\Presentation\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ReportController extends ApiController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'year'  => ['required', 'integer', 'min:2000'],
        ]);

        $result = $this->queryBus->dispatch(new GetMonthlyStuntingReportQuery(
            month: (int) $validated['month'],
            year: (int) $validated['year'],
        ));

        return $this->success($result, 'Monthly report retrieved successfully');
    }
}
