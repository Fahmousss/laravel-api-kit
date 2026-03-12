<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Dashboards\Queries\GetGeoTaggedStuntingSummary\GetGeoTaggedStuntingSummaryQuery;
use App\Infrastructure\Posyandus\Models\Posyandu;
use App\Presentation\ViewModels\Web\PublicMapViewModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class PublicMapController extends WebController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(Request $request): View
    {
        $kelurahan = $request->query('kelurahan');

        // Cast to string or keep as null
        $kelurahan = is_string($kelurahan) && $kelurahan !== '' ? $kelurahan : null;

        /** @var array<int, array{id: int, lat: float, lng: float, status: string}> $geoSummary */
        $geoSummary = $this->queryBus->dispatch(new GetGeoTaggedStuntingSummaryQuery(
            posyanduId: null,
            kelurahan: $kelurahan
        ));

        // Get unique kelurahan list for the filter dropdown
        $kelurahans = Posyandu::query()
            ->select('location')
            ->distinct()
            ->whereNotNull('location')
            ->orderBy('location')
            ->pluck('location');

        return view('welcome', [
            'viewModel' => new PublicMapViewModel(
                geoSummary: $geoSummary,
                kelurahans: $kelurahans,
                selectedKelurahan: $kelurahan
            ),
        ]);
    }
}
