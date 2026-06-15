<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'first_name',
        'last_name',
        'email',
        'shipping_address',
        'city',
        'postal_code',
        'order_notes',
        'payment_method',
        'subtotal',
        'shipping_cost',
        'total_paid',
        'status'
    ];

    /**
     * Relasi ke model OrderItem (Satu pesanan memiliki banyak item produk terlampir)
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
