<?php

declare(strict_types=1);

namespace App\Domain\Measurements\Services;

use Illuminate\Support\Carbon;

final class StuntingCalculatorService implements StuntingCalculatorServiceInterface
{
    /**
     * Calculate Z-Score for Height-for-Age (TB/U)
     */
    public function calculateZScore(float $heightCm, string $gender, Carbon $dob, Carbon $measurementDate): float
    {
        $ageInMonths = $measurementDate->diffInMonths($dob);

        $medianHeight = 50 + ($ageInMonths * 1.5);
        if ($gender === 'L') {
            $medianHeight += 1.5;
        }

        $stdDev = 2.5 + ($ageInMonths * 0.1);

        $zScore = ($heightCm - $medianHeight) / $stdDev;

        return max(-6.0, min(6.0, round($zScore, 2)));
    }

    /**
     * Determine stunting status based on Z-Score
     */
    public function determineStatus(float $zScore): string
    {
        if ($zScore < -3.0) {
            return 'Severely Stunted';
        }

        if ($zScore >= -3.0 && $zScore < -2.0) {
            return 'Stunted';
        }

        if ($zScore >= -2.0 && $zScore <= 3.0) {
            return 'Normal';
        }

        return 'Tinggi (Normal)';
    }

    /**
     * Calculate both Z-Score and Status
     *
     * @return array{z_score: float, status: string}
     */
    public function evaluate(float $heightCm, string $gender, Carbon $dob, Carbon $measurementDate): array
    {
        $zScore = $this->calculateZScore($heightCm, $gender, $dob, $measurementDate);
        $status = $this->determineStatus($zScore);

        return [
            'z_score' => $zScore,
            'status'  => $status,
        ];
    }
}
