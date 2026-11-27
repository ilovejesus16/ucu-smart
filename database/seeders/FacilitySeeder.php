<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Gymnasium',
                'description' => 'Main university gymnasium.',
                'location' => 'Urdaneta City University',
                'capacity' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Mini Gym',
                'description' => 'University mini gymnasium.',
                'location' => 'Urdaneta City University',
                'capacity' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Quadrangle',
                'description' => 'University quadrangle and open event area.',
                'location' => 'Urdaneta City University',
                'capacity' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Square Garden',
                'description' => 'University square garden event area.',
                'location' => 'Urdaneta City University',
                'capacity' => null,
                'is_active' => true,
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::updateOrCreate(
                ['name' => $facility['name']],
                $facility
            );
        }
    }
}
