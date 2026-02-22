<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price',
        'price_hot', 'price_cold', 'has_variants', 'image',
        'is_active', 'is_featured', 'is_new', 'is_promo',
        'promo_price', 'is_seasonal', 'seasonal_label', 'sort_order',
    ];

    protected $casts = [
        'has_variants' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_promo' => 'boolean',
        'is_seasonal' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
                // Ensure unique slug
                $count = static::where('slug', $product->slug)->count();
                if ($count > 0) {
                    $product->slug .= '-' . ($count + 1);
                }
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
                $count = static::where('slug', $product->slug)->where('id', '!=', $product->id)->count();
                if ($count > 0) {
                    $product->slug .= '-' . ($count + 1);
                }
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSeasonal($query)
    {
        return $query->where('is_seasonal', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getDisplayPriceAttribute(): int
    {
        if ($this->is_promo && $this->promo_price) {
            return $this->promo_price;
        }
        return $this->price;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->display_price, 0, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return asset('images/no-product.svg');

        if (file_exists(public_path('images/' . $this->image)))
            return asset('images/' . $this->image);

        if (file_exists(public_path('storage/' . $this->image)))
            return asset('storage/' . $this->image);

        return asset('images/no-product.svg');
    }

    public function getPriceForVariant(?string $variant): int
    {
        if ($variant === 'hot' && $this->price_hot) return $this->price_hot;
        if ($variant === 'cold' && $this->price_cold) return $this->price_cold;
        return $this->display_price;
    }
}
