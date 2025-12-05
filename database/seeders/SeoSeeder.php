<?php

namespace Database\Seeders;

use App\Models\SeoSetting;
use Illuminate\Database\Seeder;

class SeoSeeder extends Seeder
{
    public function run(): void
    {
        SeoSetting::updateOrCreate(
            ['id' => 1],
            [
                // Basic Meta Tags
                'meta_title' => "Mary's Lace n Craft | Wholesale & Retail Craft Supplies in La Puente, CA",
                'meta_description' => "Your one-stop shop for wholesale and retail craft supplies in La Puente, CA. Premium ribbons, laces, flowers, baskets & party favors for weddings, quinceañeras, baby showers & all events. Call (626) 918-8511.",
                'meta_keywords' => 'craft supplies, ribbons, laces, party favors, wedding supplies, quinceañera, baby shower, La Puente, wholesale crafts, retail crafts, flowers, baskets',
                'canonical_url' => config('app.url'),
                
                // Open Graph
                'og_title' => "Mary's Lace n Craft | Premium Craft Supplies",
                'og_description' => "Discover premium ribbons, laces, flowers, baskets & party favors for all your special events. Wholesale & retail in La Puente, CA.",
                'og_type' => 'local_business',
                'og_site_name' => "Mary's Lace n Craft",
                
                // Twitter Card
                'twitter_card' => 'summary_large_image',
                'twitter_title' => "Mary's Lace n Craft | Craft Supplies",
                'twitter_description' => "Premium ribbons, laces & party favors for weddings, quinceañeras & all events. La Puente, CA.",
                
                // Local Business
                'business_name' => "Mary's Lace n Craft",
                'business_type' => 'Store',
                'business_description' => "Wholesale and retail craft supplies store specializing in lace, ribbons, baskets, flowers, and party favors for all events including weddings, quinceañeras, baby showers, and birthday parties.",
                'business_phone' => '(626) 918-8511',
                'business_address_street' => '1629 N Hacienda Blvd',
                'business_address_city' => 'La Puente',
                'business_address_state' => 'CA',
                'business_address_zip' => '91744',
                'business_address_country' => 'US',
                'business_latitude' => 34.0330,
                'business_longitude' => -117.9497,
                'business_price_range' => '$$',
                'business_hours' => [
                    ['dayOfWeek' => 'Monday', 'opens' => '09:00', 'closes' => '18:00'],
                    ['dayOfWeek' => 'Tuesday', 'opens' => '09:00', 'closes' => '18:00'],
                    ['dayOfWeek' => 'Wednesday', 'opens' => '09:00', 'closes' => '18:00'],
                    ['dayOfWeek' => 'Thursday', 'opens' => '09:00', 'closes' => '18:00'],
                    ['dayOfWeek' => 'Friday', 'opens' => '09:00', 'closes' => '18:00'],
                    ['dayOfWeek' => 'Saturday', 'opens' => '10:00', 'closes' => '17:00'],
                ],
                
                'is_active' => true,
            ]
        );
    }
}

