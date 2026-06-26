<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $couriers = [
            ['code' => 'jne', 'name' => 'JNE'],
            ['code' => 'jnt', 'name' => 'J&T'],
            ['code' => 'pos', 'name' => 'POS Indonesia'],
            ['code' => 'sicepat', 'name' => 'SiCepat'],
        ];

        foreach ($couriers as $courier) {
            Courier::updateOrCreate(
                ['code' => $courier['code']],
                ['name' => $courier['name'], 'is_active' => true]
            );
        }
    }
}
