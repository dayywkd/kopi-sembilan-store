<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'transaction_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'shipping_address',
        'city',
        'postal_code',
        'order_notes',
        'payment_method',
        'subtotal',
        'shipping_cost',
        'total_paid',
        'status',
        'biteship_area_id',
        'biteship_area_name',
        'tracking_number',
        'courier',
        'shipping_service'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * Relasi ke model OrderItem (Satu pesanan memiliki banyak item produk terlampir)
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Accessor untuk format Rupiah standar tanpa kode unik.
     */
    public function getFormattedTotalPaidAttribute()
    {
        return 'Rp ' . number_format((int) $this->total_paid, 0, ',', '.');
    }
}
