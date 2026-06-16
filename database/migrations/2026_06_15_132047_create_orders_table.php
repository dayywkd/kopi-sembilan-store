<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->text('shipping_address');
            $table->string('city');
            $table->string('postal_code');
            $table->text('order_notes')->nullable();
            $table->enum('payment_method', ['Bank Transfer', 'QRIS']);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2);
            $table->decimal('total_paid', 12, 2);
            $table->enum('status', ['Awaiting Payment', 'Paid', 'Packing', 'Shipped'])->default('Awaiting Payment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
