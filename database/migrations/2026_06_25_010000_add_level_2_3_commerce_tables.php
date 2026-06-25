<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('grind_size')->default('100gr');
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();
            $table->unique(['user_id', 'product_id', 'grind_size'], 'cart_user_product_size_unique');
            $table->unique(['session_id', 'product_id', 'grind_size'], 'cart_session_product_size_unique');
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('value', 12, 2);
            $table->decimal('minimum_subtotal', 12, 2)->default(0);
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Alamat Utama');
            $table->string('recipient_name');
            $table->string('phone', 25);
            $table->text('address');
            $table->string('city');
            $table->string('postal_code', 15);
            $table->string('biteship_area_id')->nullable();
            $table->string('biteship_area_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('coupon_code')->nullable()->after('shipping_cost');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('coupon_code');
            $table->string('payment_proof_path')->nullable()->after('payment_method');
            $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof_path');
            $table->json('tracking_events')->nullable()->after('tracking_number');
            $table->timestamp('tracking_synced_at')->nullable()->after('tracking_events');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('comment');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'coupon_code',
                'discount_amount',
                'payment_proof_path',
                'payment_proof_uploaded_at',
                'tracking_events',
                'tracking_synced_at',
            ]);
        });

        Schema::dropIfExists('product_images');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('cart_items');
    }
};
