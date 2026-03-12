<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Admin;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary\GetGeoTaggedStuntingSummaryQuery;
use App\Presentation\ViewModels\Web\Dashboard\DashboardViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final readonly class DashboardController
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {}

    public function __invoke(Request $request): View
    {
        $geoSummaryArray = $this->queryBus->dispatch(new GetGeoTaggedStuntingSummaryQuery());
        $viewModel       = new DashboardViewModel($geoSummaryArray);

        return view('admin.dashboard', [
            'viewModel' => $viewModel,
            'user'      => $request->user(),
        ]);
    }
}
