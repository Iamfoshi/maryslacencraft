<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    protected $fillable = [
        'badge_text',
        'title_line1',
        'title_line2',
        'lead_paragraph',
        'content',
        'image',
        'stat_number',
        'stat_label',
        'feature1_title',
        'feature1_description',
        'feature2_title',
        'feature2_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getActive(): ?self
    {
        return self::active()->first();
    }
}
