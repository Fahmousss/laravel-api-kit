<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Infrastructure\Measurements\Models\AnthropometryStandard;
use Illuminate\Database\Seeder;

final class AnthropometryStandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Simple linear mock for WHO standard Length/Height for age 0-60m
        // Median roughly starts around 50cm and grows by ~0.8cm a month
        // SD is roughly 2.0 to 2.5
        
        $genders = ['L', 'P'];

        foreach ($genders as $gender) {
            for ($age = 0; $age <= 60; $age++) {
                $median = ($gender === 'L' ? 50.5 : 49.5) + ($age * 0.85);

                $sd = 2.0 + ($age * 0.05);

                AnthropometryStandard::query()->updateOrCreate(
                    [
                        'gender'        => $gender,
                        'age_in_months' => $age,
                    ],
                    [
                        'minus_3_sd' => round($median - (3 * $sd), 2),
                        'minus_2_sd' => round($median - (2 * $sd), 2),
                        'minus_1_sd' => round($median - (1 * $sd), 2),
                        'median'     => round($median, 2),
                        'plus_1_sd'  => round($median + (1 * $sd), 2),
                        'plus_2_sd'  => round($median + (2 * $sd), 2),
                        'plus_3_sd'  => round($median + (3 * $sd), 2),
                    ]
                );
            }
        }
    }
}
