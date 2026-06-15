<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function up(): void
    {
        // Seeder menggunakan method up atau run? Standard Laravel adalah run()
    }

    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'shipping_threshold'],
            ['value' => '500000']
        );

        Setting::updateOrCreate(
            ['key' => 'shipping_flat_rate'],
            ['value' => '15000']
        );

        Setting::updateOrCreate(
            ['key' => 'grind_sizes'],
            ['value' => json_encode([
                'WHOLE BEAN',
                'ESPRESSO (FINE)',
                'POUR OVER (MEDIUM)',
                'FRENCH PRESS (COARSE)'
            ])]
        );
    }
}
