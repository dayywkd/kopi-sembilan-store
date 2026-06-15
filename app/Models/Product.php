<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status'
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
}
