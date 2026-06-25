<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_subtotal',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function discountFor(float $subtotal): float
    {
        if (!$this->is_active || $subtotal < (float) $this->minimum_subtotal) {
            return 0;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 0;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return 0;
        }

        $discount = $this->type === 'percent'
            ? $subtotal * ((float) $this->value / 100)
            : (float) $this->value;

        return min($subtotal, max(0, $discount));
    }
}
