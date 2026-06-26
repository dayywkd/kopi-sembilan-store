<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Order;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cari semua order yang uuid-nya null atau kosong, lalu generate uuid baru
        Order::whereNull('uuid')
            ->orWhere('uuid', '')
            ->chunkById(100, function ($orders) {
                foreach ($orders as $order) {
                    $order->uuid = (string) Str::uuid();
                    $order->save();
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu melakukan apa-apa saat rollback
    }
};
