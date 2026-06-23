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
        'tracking_number'
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
     * Accessor untuk mendapatkan format Rupiah dengan menyorot 3 digit kode unik terakhir (Opsi C).
     */
    public function getFormattedTotalPaidAttribute()
    {
        $amount = (int) $this->total_paid;
        $base = floor($amount / 1000) * 1000;
        $unique = $amount % 1000;
        
        $baseFormatted = number_format($base, 0, ',', '.');
        $baseStr = substr($baseFormatted, 0, -4);
        $uniqueStr = str_pad($unique, 3, '0', STR_PAD_LEFT);
        
        if ($base >= 1000) {
            return 'Rp. ' . $baseStr . '.' . '<span class="font-bold text-[#121212]">' . $uniqueStr . '</span>';
        } else {
            return 'Rp. <span class="font-bold text-[#121212]">' . $uniqueStr . '</span>';
        }
    }
}
