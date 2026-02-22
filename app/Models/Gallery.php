<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    protected $fillable = ['title', 'description', 'group', 'product_id', 'price', 'image', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeGroup($query, string $name)
    {
        return $query->where('group', $name);
    }

    public function getFormattedPriceAttribute(): ?string
    {
        if (!$this->price) return null;
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return asset('images/no-product.svg');

        if (file_exists(public_path('images/' . $this->image)))
            return asset('images/' . $this->image);

        if (file_exists(public_path('storage/' . $this->image)))
            return asset('storage/' . $this->image);

        if (file_exists(public_path('images/special-event/' . $this->image)))
            return asset('images/special-event/' . $this->image);

        return asset('images/no-product.svg');
    }

    public static function groups(): array
    {
        return ['menu' => 'Menu Utama', 'special_event' => 'Event Spesial'];
    }
}
