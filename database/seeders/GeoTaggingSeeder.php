<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Infrastructure\Children\Models\Child;
use App\Infrastructure\Measurements\Models\Measurement;
use App\Infrastructure\Posyandus\Models\Posyandu;
use Illuminate\Database\Seeder;

final class GeoTaggingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define clusters for different Kelurahan in Kecamatan Ilir Timur II
        $kelurahans = [
            [
                'name' => '11 Ilir',
                'lat'  => -2.9785,
                'lng'  => 104.7660,
            ],
            [
                'name' => '9 Ilir',
                'lat'  => -2.9730,
                'lng'  => 104.7610,
            ],
            [
                'name' => '8 Ilir',
                'lat'  => -2.9680,
                'lng'  => 104.7680,
            ],
            [
                'name' => 'Lawang Kidul',
                'lat'  => -2.9760,
                'lng'  => 104.7730,
            ],
            [
                'name' => 'Duku',
                'lat'  => -2.9810,
                'lng'  => 104.7700,
            ],
        ];

        foreach ($kelurahans as $kelurahan) {
            // Generate 1 Posyandu for each Kelurahan
            $posyandu = Posyandu::factory()->create([
                'name'     => 'Posyandu '.$kelurahan['name'].' '.fake()->companySuffix(),
                'district' => 'Ilir Timur II',
                'location' => 'Kelurahan '.$kelurahan['name'],
            ]);

            // Generate 15 children for this Posyandu
            $children = Child::factory()->count(15)->create([
                'posyandu_id' => $posyandu->id,
            ]);

            foreach ($children as $child) {
                // Generate 1 measurement for each child, clustered around the Kelurahan center (approx 0 - 400m radius)
                Measurement::factory()->create([
                    'child_id' => $child->id,
                    'lat'      => $kelurahan['lat'] + fake()->randomFloat(6, -0.003, 0.003),
                    'lng'      => $kelurahan['lng'] + fake()->randomFloat(6, -0.003, 0.003),
                ]);
            }
        }
    }
}
