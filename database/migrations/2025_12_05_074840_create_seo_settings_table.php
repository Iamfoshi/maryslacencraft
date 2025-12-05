<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            
            // Basic Meta Tags
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            
            // Open Graph (Facebook)
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            $table->string('og_site_name')->nullable();
            
            // Twitter Card
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->string('twitter_site')->nullable();
            
            // Local Business (Schema.org)
            $table->string('business_name')->nullable();
            $table->string('business_type')->default('LocalBusiness');
            $table->text('business_description')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_address_street')->nullable();
            $table->string('business_address_city')->nullable();
            $table->string('business_address_state')->nullable();
            $table->string('business_address_zip')->nullable();
            $table->string('business_address_country')->default('US');
            $table->decimal('business_latitude', 10, 7)->nullable();
            $table->decimal('business_longitude', 10, 7)->nullable();
            $table->string('business_logo')->nullable();
            $table->json('business_hours')->nullable();
            $table->string('business_price_range')->nullable();
            
            // Social Links
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('pinterest_url')->nullable();
            $table->string('yelp_url')->nullable();
            $table->string('google_business_url')->nullable();
            
            // Additional SEO
            $table->text('robots_txt')->nullable();
            $table->text('custom_head_scripts')->nullable();
            $table->text('custom_body_scripts')->nullable();
            $table->string('google_analytics_id')->nullable();
            $table->string('google_tag_manager_id')->nullable();
            $table->string('facebook_pixel_id')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
