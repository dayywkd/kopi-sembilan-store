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
        \App\Models\User::create([
            'name' => 'Admin Toko Kopi 9',
            'email' => 'admin@kopisembilan.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Budi Customer',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        // Panggil seeder custom kita untuk Toko Kopi Sembilan
        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
