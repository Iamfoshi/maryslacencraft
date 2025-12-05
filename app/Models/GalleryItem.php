<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'title',
        'image',
        'gradient_from',
        'gradient_via',
        'gradient_to',
        'is_large',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_large' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getGradientClassAttribute(): string
    {
        return "bg-gradient-to-br from-{$this->gradient_from} via-{$this->gradient_via} to-{$this->gradient_to}";
    }
}
