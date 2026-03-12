<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Infrastructure\Posyandus\Models\Posyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Posyandu>
 */
final class PosyanduFactory extends Factory
{
    protected $model = Posyandu::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'     => 'Posyandu '.fake()->company(),
            'district' => 'Ilir Timur II',
        ];
    }
}
