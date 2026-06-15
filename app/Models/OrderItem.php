<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'grind_size',
        'quantity',
        'price'
    ];

    /**
     * Relasi ke model Order (Item ini dimiliki oleh satu order tertentu)
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke model Product (Item ini merujuk ke suatu produk aktif)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
