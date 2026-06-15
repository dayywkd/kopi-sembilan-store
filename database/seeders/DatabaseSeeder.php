<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder custom kita untuk Toko Kopi Sembilan
        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
