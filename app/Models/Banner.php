<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'subtitle', 'image', 'link', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return '';

        if (file_exists(public_path('images/' . $this->image)))
            return asset('images/' . $this->image);

        if (file_exists(public_path('storage/' . $this->image)))
            return asset('storage/' . $this->image);

        return '';
    }
}
