<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use App\Models\AboutSection;
use App\Models\GalleryItem;
use App\Models\HeroSection;
use App\Models\ProductCategory;
use App\Models\SeoSetting;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    // Get SEO data
    $seo = SeoSetting::getActive();
    
    // Get site content
    $hero = HeroSection::getActive();
    $about = AboutSection::getActive();
    $categories = ProductCategory::active()->ordered()->get();
    $gallery = GalleryItem::active()->ordered()->get();
    $testimonials = Testimonial::active()->ordered()->get();
    
    // Get site settings
    $settings = [
        'site_name' => SiteSetting::get('site_name', "Mary's Lace n Craft"),
        'location_name' => SiteSetting::get('location_name', 'South Hill Square'),
        'phone' => SiteSetting::get('phone', '(626) 918-8511'),
        'address_line1' => SiteSetting::get('address_line1', 'South Hills Shopping Center'),
        'address_line2' => SiteSetting::get('address_line2', '1629 N Hacienda Blvd, La Puente, CA 91744'),
        'hours' => [
            'monday' => SiteSetting::get('hours_monday', '9 AM – 7 PM'),
            'tuesday' => SiteSetting::get('hours_tuesday', '9 AM – 7 PM'),
            'wednesday' => SiteSetting::get('hours_wednesday', '9 AM – 7 PM'),
            'thursday' => SiteSetting::get('hours_thursday', '9 AM – 7 PM'),
            'friday' => SiteSetting::get('hours_friday', '9 AM – 7 PM'),
            'saturday' => SiteSetting::get('hours_saturday', '10 AM – 6 PM'),
            'sunday' => SiteSetting::get('hours_sunday', '12 PM – 5 PM'),
        ],
        'footer_tagline' => SiteSetting::get('footer_tagline'),
    ];

    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
        'seo' => $seo ? [
            'title' => $seo->meta_title,
            'description' => $seo->meta_description,
            'keywords' => $seo->meta_keywords,
            'ogTitle' => $seo->og_title ?? $seo->meta_title,
            'ogDescription' => $seo->og_description ?? $seo->meta_description,
            'ogImage' => $seo->og_image ? asset('storage/' . $seo->og_image) : null,
            'ogType' => $seo->og_type,
            'ogSiteName' => $seo->og_site_name,
            'twitterCard' => $seo->twitter_card,
            'twitterSite' => $seo->twitter_site,
            'canonicalUrl' => $seo->canonical_url ?? config('app.url'),
            'localBusiness' => $seo->getLocalBusinessJsonLd(),
            'googleAnalyticsId' => $seo->google_analytics_id,
            'googleTagManagerId' => $seo->google_tag_manager_id,
            'facebookPixelId' => $seo->facebook_pixel_id,
            'customHeadScripts' => $seo->custom_head_scripts,
            'customBodyScripts' => $seo->custom_body_scripts,
            'googleSiteVerification' => $seo->google_site_verification,
            'bingSiteVerification' => $seo->bing_site_verification,
            'pinterestSiteVerification' => $seo->pinterest_site_verification,
            'socialLinks' => [
                'facebook' => $seo->facebook_url,
                'instagram' => $seo->instagram_url,
                'twitter' => $seo->twitter_url,
                'pinterest' => $seo->pinterest_url,
                'yelp' => $seo->yelp_url,
                'google' => $seo->google_business_url,
            ],
        ] : null,
        'hero' => $hero,
        'about' => $about,
        'categories' => $categories,
        'gallery' => $gallery,
        'testimonials' => $testimonials,
        'settings' => $settings,
    ]);
})->name('home');

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Contact Form
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
