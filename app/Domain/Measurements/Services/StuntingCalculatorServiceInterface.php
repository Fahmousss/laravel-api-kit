<?php

declare(strict_types=1);

namespace App\Domain\Measurements\Services;

use Illuminate\Support\Carbon;

interface StuntingCalculatorServiceInterface
{
    /**
     * Calculate Z-Score for Height-for-Age (TB/U)
     */
    public function calculateZScore(float $heightCm, string $gender, Carbon $dob, Carbon $measurementDate): float;

    /**
     * Determine stunting status based on Z-Score
     */
    public function determineStatus(float $zScore): string;

    /**
     * Evaluate both Z-Score and Status
     *
     * @return array{z_score: float, status: string}
     */
    public function evaluate(float $heightCm, string $gender, Carbon $dob, Carbon $measurementDate): array;
}
