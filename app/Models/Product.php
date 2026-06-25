<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'sizes',
        'image_path',
        'roast_level',
        'altitude',
        'sensory_notes',
        'status',
        'stock',
        'is_best_seller'
    ];

    protected $casts = [
        'sizes' => 'array',
    ];

    /**
     * Relasi ke model Category (Satu produk milik satu kategori)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke model Review (Satu produk memiliki banyak ulasan)
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function allReviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Mendapatkan rata-rata rating
     */
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Mendapatkan jumlah ulasan
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
}
