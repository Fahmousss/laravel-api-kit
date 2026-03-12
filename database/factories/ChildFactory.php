<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Infrastructure\Children\Models\Child;
use App\Infrastructure\Posyandus\Models\Posyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Child>
 */
final class ChildFactory extends Factory
{
    protected $model = Child::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'posyandu_id' => Posyandu::factory(),
            'nik'         => fake()->numerify('16710###########'),
            'name'        => fake()->name(),
            'dob'         => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'gender'      => fake()->randomElement(['L', 'P']),
            'parent_name' => fake()->name(),
        ];
    }
}
