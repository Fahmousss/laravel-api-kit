<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Kader;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary\GetGeoTaggedStuntingSummaryQuery;
use App\Application\Features\Posyandus\Queries\GetPosyandusByUserId\GetPosyandusByUserIdQuery;
use App\Presentation\ViewModels\Web\Dashboard\DashboardViewModel;
use App\Presentation\ViewModels\Web\MasterData\PosyanduListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final readonly class DashboardController
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {}

    public function __invoke(Request $request): View
    {
        $userId     = $request->user()->id;
        $posyanduId = $request->user()->posyandu_id;

        $paginatedResult = $this->queryBus->dispatch(new GetPosyandusByUserIdQuery(
            userId: $userId,
            page: 1,
            perPage: 10
        ));

        $viewModel = new PosyanduListViewModel($paginatedResult);

        $geoSummary = [];
        if ($posyanduId !== null) {
            $geoSummary = $this->queryBus->dispatch(new GetGeoTaggedStuntingSummaryQuery(
                posyanduId: $posyanduId
            ));
        }

        $dashboardViewModel = new DashboardViewModel($geoSummary);

        return view('kader.dashboard', [
            'viewModel'          => $viewModel,
            'dashboardViewModel' => $dashboardViewModel,
            'user'               => $request->user(),
        ]);
    }
}
