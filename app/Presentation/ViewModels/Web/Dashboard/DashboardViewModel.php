<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels\Web\Dashboard;

final readonly class DashboardViewModel
{
    public int $totalSevereStunting;

    public int $totalStunting;

    /**
     * @param array<int, array{id: int, lat: float, lng: float, status: null|string}> $geoSummary
     */
    public function __construct(public array $geoSummary)
    {
        $this->totalSevereStunting = collect($this->geoSummary)->where('status', 'Severely Stunted')->count();
        $this->totalStunting       = collect($this->geoSummary)->where('status', 'Stunted')->count();
    }
}
