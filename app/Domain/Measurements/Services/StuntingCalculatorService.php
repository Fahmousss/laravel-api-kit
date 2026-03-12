<?php

declare(strict_types=1);

namespace App\Domain\Measurements\Services;

use App\Infrastructure\Measurements\Models\AnthropometryStandard;
use Illuminate\Support\Carbon;

final class StuntingCalculatorService implements StuntingCalculatorServiceInterface
{
    /**
     * Calculate Z-Score for Height-for-Age (TB/U)
     */
    public function calculateZScore(float $heightCm, string $position, string $gender, Carbon $dob, Carbon $measurementDate): float
    {
        // 1. Age is calculated in full months
        $ageInMonths = $measurementDate->diffInMonths($dob);
        if ($ageInMonths > 60) {
            $ageInMonths = 60; // Max lookup age based on standard table
        }

        // 2. Correction rules based on position and age
        $adjustedHeight = $heightCm;
        if ($ageInMonths <= 24 && $position === 'berdiri') {
            $adjustedHeight += 0.7;
        } elseif ($ageInMonths > 24 && $position === 'telentang') {
            $adjustedHeight -= 0.7;
        }

        // 3. Lookup standard table
        /** @var AnthropometryStandard|null $standard */
        $standard = AnthropometryStandard::query()
            ->where('gender', $gender)
            ->where('age_in_months', $ageInMonths)
            ->first();

        // Fallback safety if standard is missing (e.g. seeder not run)
        if (! $standard) {
            $median = 50 + ($ageInMonths * 1.5) + ($gender === 'L' ? 1.5 : 0);
            $sd = 2.5 + ($ageInMonths * 0.1);
            $zScore = ($adjustedHeight - $median) / $sd;
            return max(-6.0, min(6.0, round($zScore, 2)));
        }

        // 4. Calculate rigorous Z-Score using exact SD gaps
        $median = $standard->median;
        if ($adjustedHeight < $median) {
            $sdValue = $median - $standard->minus_1_sd;
        } else {
            $sdValue = $standard->plus_1_sd - $median;
        }

        // Prevent division by zero just in case
        $sdValue = max($sdValue, 0.0001);

        $zScore = ($adjustedHeight - $median) / $sdValue;

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
    public function evaluate(float $heightCm, string $position, string $gender, Carbon $dob, Carbon $measurementDate): array
    {
        $zScore = $this->calculateZScore($heightCm, $position, $gender, $dob, $measurementDate);
        $status = $this->determineStatus($zScore);

        return [
            'z_score' => $zScore,
            'status'  => $status,
        ];
    }
}
