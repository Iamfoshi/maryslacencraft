<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SeoSetting extends Model
{
    protected $fillable = [
        // Basic Meta Tags
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        
        // Open Graph
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'og_site_name',
        
        // Twitter Card
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_site',
        
        // Local Business
        'business_name',
        'business_type',
        'business_description',
        'business_phone',
        'business_email',
        'business_address_street',
        'business_address_city',
        'business_address_state',
        'business_address_zip',
        'business_address_country',
        'business_latitude',
        'business_longitude',
        'business_logo',
        'business_hours',
        'business_price_range',
        
        // Social Links
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'pinterest_url',
        'yelp_url',
        'google_business_url',
        
        // Additional SEO
        'robots_txt',
        'google_site_verification',
        'bing_site_verification',
        'pinterest_site_verification',
        'custom_head_scripts',
        'custom_body_scripts',
        'google_analytics_id',
        'google_tag_manager_id',
        'facebook_pixel_id',
        
        'is_active',
    ];

    protected $casts = [
        'business_hours' => 'array',
        'business_latitude' => 'decimal:7',
        'business_longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active SEO settings
     */
    public static function getActive(): ?self
    {
        return Cache::rememberForever('seo_settings', function () {
            return self::where('is_active', true)->first();
        });
    }

    /**
     * Clear SEO settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('seo_settings');
    }

    protected static function booted(): void
    {
        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }

    /**
     * Generate JSON-LD structured data for local business
     */
    public function getLocalBusinessJsonLd(): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => $this->business_type ?? 'LocalBusiness',
            'name' => $this->business_name,
            'description' => $this->business_description,
            'telephone' => $this->business_phone,
            'email' => $this->business_email,
            'url' => config('app.url'),
        ];

        if ($this->business_logo) {
            $data['logo'] = asset('storage/' . $this->business_logo);
            $data['image'] = asset('storage/' . $this->business_logo);
        }

        if ($this->business_address_street) {
            $data['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->business_address_street,
                'addressLocality' => $this->business_address_city,
                'addressRegion' => $this->business_address_state,
                'postalCode' => $this->business_address_zip,
                'addressCountry' => $this->business_address_country,
            ];
        }

        if ($this->business_latitude && $this->business_longitude) {
            $data['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $this->business_latitude,
                'longitude' => $this->business_longitude,
            ];
        }

        if ($this->business_hours) {
            $data['openingHoursSpecification'] = $this->business_hours;
        }

        if ($this->business_price_range) {
            $data['priceRange'] = $this->business_price_range;
        }

        // Social links
        $sameAs = array_filter([
            $this->facebook_url,
            $this->instagram_url,
            $this->pinterest_url,
            $this->yelp_url,
            $this->google_business_url,
        ]);

        if (!empty($sameAs)) {
            $data['sameAs'] = array_values($sameAs);
        }

        return $data;
    }

    /**
     * Get meta tags array for rendering
     */
    public function getMetaTags(): array
    {
        $tags = [];
        
        if ($this->meta_description) {
            $tags['description'] = $this->meta_description;
        }
        
        if ($this->meta_keywords) {
            $tags['keywords'] = $this->meta_keywords;
        }

        // Open Graph
        if ($this->og_title || $this->meta_title) {
            $tags['og:title'] = $this->og_title ?? $this->meta_title;
        }
        if ($this->og_description || $this->meta_description) {
            $tags['og:description'] = $this->og_description ?? $this->meta_description;
        }
        if ($this->og_image) {
            $tags['og:image'] = asset('storage/' . $this->og_image);
        }
        $tags['og:type'] = $this->og_type ?? 'website';
        if ($this->og_site_name) {
            $tags['og:site_name'] = $this->og_site_name;
        }
        $tags['og:url'] = config('app.url');

        // Twitter Card
        $tags['twitter:card'] = $this->twitter_card ?? 'summary_large_image';
        if ($this->twitter_title || $this->meta_title) {
            $tags['twitter:title'] = $this->twitter_title ?? $this->meta_title;
        }
        if ($this->twitter_description || $this->meta_description) {
            $tags['twitter:description'] = $this->twitter_description ?? $this->meta_description;
        }
        if ($this->twitter_image || $this->og_image) {
            $tags['twitter:image'] = $this->twitter_image 
                ? asset('storage/' . $this->twitter_image) 
                : ($this->og_image ? asset('storage/' . $this->og_image) : null);
        }
        if ($this->twitter_site) {
            $tags['twitter:site'] = $this->twitter_site;
        }

        return array_filter($tags);
    }
}
