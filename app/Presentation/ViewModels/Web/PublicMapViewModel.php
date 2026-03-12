<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels\Web;

use Illuminate\Support\Collection;

final readonly class PublicMapViewModel
{
    /**
     * @param array<int, array{id: int, lat: float, lng: float, status: string}> $geoSummary
     * @param Collection<int, string> $kelurahans
     */
    public function __construct(
        public array $geoSummary,
        public Collection $kelurahans,
        public ?string $selectedKelurahan = null,
    ) {}

    public function getTotalStunting(): int
    {
        return count(array_filter($this->geoSummary, static fn (array $point): bool => $point['status'] === 'Stunted' || $point['status'] === 'Terindikasi Stunting'));
    }

    public function getTotalSevereStunting(): int
    {
        return count(array_filter($this->geoSummary, static fn (array $point): bool => $point['status'] === 'Severely Stunted'));
    }
}
