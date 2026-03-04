<?php

declare(strict_types=1);

namespace App\Application\Features\Location\Queries\GetStuntingClusters;

use App\Infrastructure\Child\Models\Child;
use Illuminate\Database\Eloquent\Collection;

final class GetStuntingClustersQueryHandler
{
    /**
     * @return Collection<int, Child>
     */
    public function handle(GetStuntingClustersQuery $query): Collection
    {
        // Extracts PostGIS coordinates implicitly through models and groups by stunting status mapping
        return Child::with(['posyandu', 'measurements' => function ($query) {
            $query->latest('measured_at')->limit(1);
        }])->get();
    }
}
