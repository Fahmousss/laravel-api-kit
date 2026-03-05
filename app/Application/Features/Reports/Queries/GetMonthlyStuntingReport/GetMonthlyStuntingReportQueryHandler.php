<?php

declare(strict_types=1);

namespace App\Application\Features\Reports\Queries\GetMonthlyStuntingReport;

use Illuminate\Support\Facades\DB;

final readonly class GetMonthlyStuntingReportQueryHandler
{
    /**
     * Direct query to DB for complex reporting rather than loading all models into memory.
     */
    public function handle(GetMonthlyStuntingReportQuery $query): array
    {
        // Simple aggregation query returning stunting statuses per posyandu for the given month/year
        $results = DB::table('measurements')
            ->join('children', 'measurements.child_id', '=', 'children.id')
            ->join('posyandus', 'children.posyandu_id', '=', 'posyandus.id')
            ->whereMonth('measurements.date', $query->month)
            ->whereYear('measurements.date', $query->year)
            ->select(
                'posyandus.name as posyandu_name',
                'posyandus.district',
                'measurements.status',
                DB::raw('count(*) as count')
            )
            ->groupBy('posyandus.name', 'posyandus.district', 'measurements.status')
            ->get();

        return $results->toArray();
    }
}
