<?php

declare(strict_types=1);

namespace App\Application\Features\Reports\Queries\GetMonthlyStuntingReport;

final readonly class GetMonthlyStuntingReportQuery
{
    public function __construct(
        public int $month,
        public int $year,
    ) {}
}
