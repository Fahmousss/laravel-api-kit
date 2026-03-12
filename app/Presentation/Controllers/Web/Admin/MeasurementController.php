<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Admin;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Measurements\Queries\GetAllMeasurements\GetAllMeasurementsQuery;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

final readonly class MeasurementController
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {}

    public function index(Request $request): View
    {
        $page    = (int) $request->query('page', 1);
        $perPage = 10;

        $result = $this->queryBus->dispatch(new GetAllMeasurementsQuery(page: $page, perPage: $perPage));

        $paginator = new LengthAwarePaginator(
            items: $result->items,
            total: $result->total,
            perPage: $result->perPage,
            currentPage: $result->currentPage,
            options: ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.measurements.index', [
            'paginator' => $paginator,
        ]);
    }
}
