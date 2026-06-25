<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::updateOrCreate(
            ['code' => 'KOPI9HEMAT'],
            [
                'type' => 'percent',
                'value' => 10,
                'minimum_subtotal' => 100000,
                'usage_limit' => null,
                'is_active' => true,
            ]
        );
    }
}
