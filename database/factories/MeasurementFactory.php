<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Infrastructure\Children\Models\Child;
use App\Infrastructure\Measurements\Models\Measurement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Measurement>
 */
final class MeasurementFactory extends Factory
{
    protected $model = Measurement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'child_id' => null, // Typically set when using factory
            'date'     => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'height'   => fake()->randomFloat(2, 45, 120),
            'position' => fake()->randomElement(['telentang', 'berdiri']),
            'weight'   => fake()->randomFloat(2, 2.5, 25),
            'lat'      => fake()->latitude(-5, 5),
            'lng'      => fake()->longitude(95, 140),
            'z_score'  => fake()->randomFloat(2, -4, 4),
            'status'   => fake()->randomElement(['Normal', 'Stunted', 'Severely Stunted', 'Tinggi (Normal)']),
        ];
    }
}
