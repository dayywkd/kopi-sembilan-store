<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $singleOrigin = Category::where('slug', 'single-origin')->first();
        $espressoBlends = Category::where('slug', 'espresso-blends')->first();
        $gear = Category::where('slug', 'gear')->first();
        $subscriptions = Category::where('slug', 'subscriptions')->first();

        // 1. Single Origin Beans
        if ($singleOrigin) {
            Product::updateOrCreate(
                ['slug' => 'padma'],
                [
                    'category_id' => $singleOrigin->id,
                    'name' => 'Padma',
                    'price' => 78000.00,
                    'sizes' => [
                        ['size' => '100gr', 'price' => 78000]
                    ],
                    'image_path' => 'images/products/solaris_bright.jpg',
                    'roast_level' => 'Light',
                    'altitude' => '1500 - 1700m',
                    'sensory_notes' => 'Jasmine, Peach, Orange Peel',
                    'status' => 'AVAILABLE'
                ]
            );

            Product::updateOrCreate(
                ['slug' => 'maresa'],
                [
                    'category_id' => $singleOrigin->id,
                    'name' => 'Maresa',
                    'price' => 78000.00,
                    'sizes' => [
                        ['size' => '100gr', 'price' => 78000]
                    ],
                    'image_path' => 'images/products/geisha_obsidian.jpg',
                    'roast_level' => 'Medium-Light',
                    'altitude' => '1450m',
                    'sensory_notes' => 'Brown Sugar, Honey, Red Apple',
                    'status' => 'AVAILABLE'
                ]
            );

            Product::updateOrCreate(
                ['slug' => 'nayara'],
                [
                    'category_id' => $singleOrigin->id,
                    'name' => 'Nayara',
                    'price' => 70000.00,
                    'sizes' => [
                        ['size' => '100gr', 'price' => 70000]
                    ],
                    'image_path' => 'images/products/solaris_bright.jpg',
                    'roast_level' => 'Light',
                    'altitude' => '1600m',
                    'sensory_notes' => 'Floral, Lime, Sweet Melon',
                    'status' => 'AVAILABLE'
                ]
            );

            Product::updateOrCreate(
                ['slug' => 'pusporeso'],
                [
                    'category_id' => $singleOrigin->id,
                    'name' => 'Pusporeso',
                    'price' => 68000.00,
                    'sizes' => [
                        ['size' => '100gr', 'price' => 68000]
                    ],
                    'image_path' => 'images/products/aurora_medium.jpg',
                    'roast_level' => 'Medium',
                    'altitude' => '1300 - 1500m',
                    'sensory_notes' => 'Caramel, Chocolate, Roasted Nut',
                    'status' => 'AVAILABLE'
                ]
            );

            Product::updateOrCreate(
                ['slug' => 'velania'],
                [
                    'category_id' => $singleOrigin->id,
                    'name' => 'Velania',
                    'price' => 78000.00,
                    'sizes' => [
                        ['size' => '100gr', 'price' => 78000]
                    ],
                    'image_path' => 'images/products/geisha_obsidian.jpg',
                    'roast_level' => 'Medium-Light',
                    'altitude' => '1400 - 1500m',
                    'sensory_notes' => 'Berry, Plum, Milk Chocolate',
                    'status' => 'AVAILABLE'
                ]
            );

            Product::updateOrCreate(
                ['slug' => 'arumpala'],
                [
                    'category_id' => $singleOrigin->id,
                    'name' => 'Arumpala',
                    'price' => 76000.00,
                    'sizes' => [
                        ['size' => '100gr', 'price' => 76000]
                    ],
                    'image_path' => 'images/products/solaris_bright.jpg',
                    'roast_level' => 'Light',
                    'altitude' => '1550m',
                    'sensory_notes' => 'White Floral, Grape, Clean Finish',
                    'status' => 'AVAILABLE'
                ]
            );

            Product::updateOrCreate(
                ['slug' => 'arum-lathi'],
                [
                    'category_id' => $singleOrigin->id,
                    'name' => 'Arum Lathi',
                    'price' => 82000.00,
                    'sizes' => [
                        ['size' => '100gr', 'price' => 82000]
                    ],
                    'image_path' => 'images/products/geisha_obsidian.jpg',
                    'roast_level' => 'Light',
                    'altitude' => '1700m',
                    'sensory_notes' => 'Rose Petals, Earl Grey, Peach Sweetness',
                    'status' => 'AVAILABLE'
                ]
            );
        }

        // 2. Espresso Blends Beans
        if ($espressoBlends) {
            Product::updateOrCreate(
                ['slug' => 'rayana'],
                [
                    'category_id' => $espressoBlends->id,
                    'name' => 'Rayana',
                    'price' => 167000.00,
                    'sizes' => [
                        ['size' => '500gr', 'price' => 167000],
                        ['size' => '1kg', 'price' => 345000]
                    ],
                    'image_path' => 'images/products/sembilan_zero.jpg',
                    'roast_level' => 'Medium-Dark',
                    'altitude' => '1200 - 1400m',
                    'sensory_notes' => 'Dark Chocolate, Almond, Thick Body',
                    'status' => 'AVAILABLE'
                ]
            );

            Product::updateOrCreate(
                ['slug' => 'damara'],
                [
                    'category_id' => $espressoBlends->id,
                    'name' => 'Damara',
                    'price' => 80000.00,
                    'sizes' => [
                        ['size' => '500gr', 'price' => 80000],
                        ['size' => '1kg', 'price' => 148000]
                    ],
                    'image_path' => 'images/products/nebula_eclipse.jpg',
                    'roast_level' => 'Medium',
                    'altitude' => '1300m',
                    'sensory_notes' => 'Brown Sugar, Milk Chocolate, Balanced',
                    'status' => 'AVAILABLE'
                ]
            );
        }

        // 3. Gear
        if ($gear) {
            Product::updateOrCreate(
                ['slug' => 'v60-copper-dripper'],
                [
                    'category_id' => $gear->id,
                    'name' => 'V60 COPPER DRIPPER',
                    'price' => 350000.00,
                    'sizes' => [
                        ['size' => 'One Size', 'price' => 350000]
                    ],
                    'image_path' => 'images/products/copper_dripper.jpg',
                    'roast_level' => 'Medium',
                    'altitude' => 'N/A',
                    'sensory_notes' => 'Premium Copper Build, Double Heat Retention',
                    'status' => 'AVAILABLE'
                ]
            );
        }

        // 4. Subscriptions
        if ($subscriptions) {
            Product::updateOrCreate(
                ['slug' => 'signature-monthly-subscription'],
                [
                    'category_id' => $subscriptions->id,
                    'name' => 'SIGNATURE MONTHLY SUBSCRIPTION',
                    'price' => 275000.00,
                    'sizes' => [
                        ['size' => '1 Month', 'price' => 275000]
                    ],
                    'image_path' => 'images/products/subscription.jpg',
                    'roast_level' => 'Medium',
                    'altitude' => 'Various Altitudes',
                    'sensory_notes' => 'Curated coffee delivered monthly',
                    'status' => 'AVAILABLE'
                ]
            );
        }
    }
}
