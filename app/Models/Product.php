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
        'main_image',
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
    
    protected $appends = ['image_urls', 'main_image_url'];

    protected $casts = [
        'base_price' => 'decimal:2',
        'available_sizes' => 'array',
        'available_colors' => 'array',
        'images' => 'array',
        'allows_customization' => 'boolean',
        'is_active' => 'boolean',
        'featured' => 'boolean',
    ];
    
    public function getImageUrlsAttribute()
    {
        $images = $this->images ?? [];
        
        if (empty($images)) {
            return [asset('images/placeholder.jpg')];
        }
        
        return collect($images)->map(function($image) {
            if (empty($image)) {
                return asset('images/placeholder.jpg');
            }
            
            // If it's already a full URL, return as is
            if (str_starts_with($image, 'http') || str_starts_with($image, '//')) {
                return $image;
            }
            
            // Clean up the path and create a relative URL so host/port mismatches don't break images
            $path = ltrim($image, '/\\');
            // If it's already a full URL, return as is
            if (str_starts_with($path, 'http') || str_starts_with($path, '//')) {
                return $path;
            }

            // Prefer the public storage URL if the file exists there
            $publicPath = public_path('storage/' . $path);
            if (file_exists($publicPath)) {
                return '/storage/' . $path;
            }

            // Otherwise, if file exists in storage/app/public, return a fallback route that serves it
            $storageFile = storage_path('app/public/' . $path);
            if (file_exists($storageFile)) {
                $filename = basename($path);
                return url('/product-image/' . $filename);
            }

            return '/images/placeholder.jpg';
        })->filter()->values()->toArray();
    }
    
    public function getMainImageUrlAttribute()
    {
        $images = $this->image_urls;
        return !empty($images) ? $images[0] : asset('images/placeholder.jpg');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getMainImageAttribute(): ?string
    {
        $images = $this->images;
        return $images && count($images) > 0 ? $images[0] : null;
    }

    public function setImagesAttribute($value)
    {
        $images = $value ?? [];
        if (!is_array($images)) {
            $images = [$images];
        }

        $normalized = array_values(array_filter(array_map(function ($img) {
            if (empty($img)) return null;
            $path = ltrim($img, '/\\');
            // If it's a full URL, skip normalization
            if (str_starts_with($path, 'http') || str_starts_with($path, '//')) {
                return $path;
            }
            // Ensure it lives under products/ and slugify filename
            $parts = explode('/', $path);
            $filename = array_pop($parts);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $base = pathinfo($filename, PATHINFO_FILENAME);
            $slug = \Illuminate\Support\Str::slug($base) . ($ext ? '.' . $ext : '');
            return 'products/' . $slug;
        }, $images)));

        $this->attributes['images'] = json_encode($normalized);
    }

    public function setMainImageAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['main_image'] = null;
            return;
        }

        $path = ltrim($value, '/\\');
        if (str_starts_with($path, 'http') || str_starts_with($path, '//')) {
            $this->attributes['main_image'] = $path;
            return;
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $base = pathinfo($path, PATHINFO_FILENAME);
        $slug = \Illuminate\Support\Str::slug($base) . ($ext ? '.' . $ext : '');
        $this->attributes['main_image'] = 'products/' . $slug;
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->base_price, 2);
    }
}