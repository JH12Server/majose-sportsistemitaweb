<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'category',
        'brand',
        'material',
        'available_sizes',
        'available_colors',
        'images',
        'allows_customization',
        'production_days',
        'is_active',
        'featured',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'available_sizes' => 'array',
        'available_colors' => 'array',
        'images' => 'array',
        'allows_customization' => 'boolean',
        'is_active' => 'boolean',
        'featured' => 'boolean',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getMainImageAttribute(): ?string
    {
        $images = $this->images;
        return $images && count($images) > 0 ? $images[0] : null;
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->base_price, 2);
    }
}