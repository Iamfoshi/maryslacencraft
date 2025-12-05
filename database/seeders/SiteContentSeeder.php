<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use App\Models\GalleryItem;
use App\Models\HeroSection;
use App\Models\ProductCategory;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SiteContentSeeder extends Seeder
{
    /**
     * Path to seed images directory
     */
    protected string $seedImagesPath;

    public function __construct()
    {
        $this->seedImagesPath = database_path('seeders/images');
    }

    public function run(): void
    {
        $this->seedSiteSettings();
        $this->seedHeroSection();
        $this->seedAboutSection();
        $this->seedProductCategories();
        $this->seedGalleryItems();
        $this->seedTestimonials();
        
        $this->command->info('✅ Site content seeded successfully!');
    }

    /**
     * Copy an image from seed images to storage
     */
    protected function copyImage(string $sourceFolder, string $filename, string $destFolder): ?string
    {
        $sourcePath = "{$this->seedImagesPath}/{$sourceFolder}/{$filename}";
        
        if (!File::exists($sourcePath)) {
            $this->command->warn("Image not found: {$sourcePath}");
            return null;
        }

        // Ensure destination directory exists
        Storage::disk('public')->makeDirectory($destFolder);
        
        $destPath = "{$destFolder}/{$filename}";
        
        // Copy file to storage
        Storage::disk('public')->put($destPath, File::get($sourcePath));
        
        return $destPath;
    }

    protected function seedSiteSettings(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => "Mary's Lace n Craft", 'type' => 'text', 'group' => 'general', 'label' => 'Site Name', 'order' => 1],
            ['key' => 'phone', 'value' => '(626) 918-8511', 'type' => 'text', 'group' => 'contact', 'label' => 'Phone Number', 'order' => 1],
            ['key' => 'address_line1', 'value' => '1629 N Hacienda Blvd', 'type' => 'text', 'group' => 'contact', 'label' => 'Address Line 1', 'order' => 2],
            ['key' => 'address_line2', 'value' => 'La Puente, CA 91744', 'type' => 'text', 'group' => 'contact', 'label' => 'Address Line 2', 'order' => 3],
            ['key' => 'hours_weekday', 'value' => 'Mon - Fri: 9am - 6pm', 'type' => 'text', 'group' => 'hours', 'label' => 'Weekday Hours', 'order' => 1],
            ['key' => 'hours_saturday', 'value' => 'Saturday: 10am - 5pm', 'type' => 'text', 'group' => 'hours', 'label' => 'Saturday Hours', 'order' => 2],
            ['key' => 'hours_sunday', 'value' => 'Sunday: Closed', 'type' => 'text', 'group' => 'hours', 'label' => 'Sunday Hours', 'order' => 3],
            ['key' => 'footer_tagline', 'value' => 'Your wholesale and retail destination for craft supplies in La Puente, CA. Lace, ribbons, baskets, flowers & party favors for all events.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Footer Tagline', 'order' => 2],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
        
        $this->command->info('  → Site settings seeded');
    }

    protected function seedHeroSection(): void
    {
        HeroSection::updateOrCreate(
            ['id' => 1],
            [
                'badge_text' => 'Wholesale & Retail Craft Supplies',
                'title_line1' => 'Where Creativity',
                'title_line2' => 'Blossoms',
                'subtitle' => 'Your one-stop shop for wholesale and retail craft supplies — lace, ribbons, baskets, flowers, and party favors for all your special events.',
                'primary_button_text' => 'Explore Collection',
                'primary_button_link' => '#products',
                'secondary_button_text' => 'Visit Our Store',
                'secondary_button_link' => '#contact',
                'background_image' => null, // Add hero background image if needed
                'is_active' => true,
            ]
        );
        
        $this->command->info('  → Hero section seeded');
    }

    protected function seedAboutSection(): void
    {
        $image = $this->copyImage('about', 'about-section.jpg', 'about');
        
        AboutSection::updateOrCreate(
            ['id' => 1],
            [
                'badge_text' => 'Our Story',
                'title_line1' => 'Crafted with Love,',
                'title_line2' => 'Curated with Care',
                'lead_paragraph' => "Mary's Lace n Craft is your trusted source for wholesale and retail craft supplies in La Puente, California.",
                'content' => "We specialize in lace, ribbons, baskets, flowers, and party favors for all your special events — from weddings and quinceañeras to baby showers and birthday parties. Whether you're a professional event planner or a DIY enthusiast, we have everything you need to make your celebrations beautiful.",
                'image' => $image,
                'stat_number' => '14+',
                'stat_label' => 'Years of Passion',
                'feature1_title' => 'Premium Quality',
                'feature1_description' => 'Hand-selected materials',
                'feature2_title' => 'Unique Selection',
                'feature2_description' => 'Rare finds & classics',
                'is_active' => true,
            ]
        );
        
        $this->command->info('  → About section seeded');
    }

    protected function seedProductCategories(): void
    {
        $categories = [
            [
                'title' => 'Ribbons',
                'description' => 'Satin, grosgrain, organza, velvet and more in every color imaginable',
                'icon' => '🎀',
                'image_file' => 'ribbons.jpg',
                'color_from' => 'pink-100',
                'color_to' => 'pink-50',
                'order' => 1,
            ],
            [
                'title' => 'Laces & Trims',
                'description' => 'Vintage-inspired and contemporary laces for elegant finishing touches',
                'icon' => '✨',
                'image_file' => 'laces.jpg',
                'color_from' => 'amber-100',
                'color_to' => 'amber-50',
                'order' => 2,
            ],
            [
                'title' => 'Party Favors',
                'description' => 'Beautiful favors and supplies for weddings, quinceañeras, baby showers & all events',
                'icon' => '🎁',
                'image_file' => 'party-favors.jpg',
                'color_from' => 'rose-100',
                'color_to' => 'rose-50',
                'order' => 3,
            ],
            [
                'title' => 'Flowers',
                'description' => 'Silk flowers, floral arrangements and supplies for stunning decorations',
                'icon' => '🌸',
                'image_file' => 'flowers.jpg',
                'color_from' => 'pink-100',
                'color_to' => 'pink-50',
                'order' => 4,
            ],
            [
                'title' => 'Baskets',
                'description' => 'Decorative baskets in all sizes perfect for gifts and arrangements',
                'icon' => '🧺',
                'image_file' => 'baskets.png',
                'color_from' => 'amber-100',
                'color_to' => 'amber-50',
                'order' => 5,
            ],
            [
                'title' => 'Event Supplies',
                'description' => 'Everything you need for birthdays, holidays, and special celebrations',
                'icon' => '🎉',
                'image_file' => 'events.jpg',
                'color_from' => 'stone-200',
                'color_to' => 'stone-100',
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            $imageFile = $category['image_file'];
            unset($category['image_file']);
            
            $image = $this->copyImage('categories', $imageFile, 'categories');
            
            ProductCategory::updateOrCreate(
                ['title' => $category['title']],
                array_merge($category, [
                    'image' => $image,
                    'is_active' => true,
                ])
            );
        }
        
        $this->command->info('  → Product categories seeded');
    }

    protected function seedGalleryItems(): void
    {
        $galleryItems = [
            [
                'title' => 'Wedding Decor',
                'image_file' => 'wedding-decor.jpg',
                'gradient_from' => 'pink-200',
                'gradient_via' => 'rose-100',
                'gradient_to' => 'white',
                'is_large' => true,
                'order' => 1,
            ],
            [
                'title' => 'Baby Showers',
                'image_file' => 'baby-shower.jpg',
                'gradient_from' => 'sky-200',
                'gradient_via' => 'blue-100',
                'gradient_to' => 'white',
                'is_large' => false,
                'order' => 2,
            ],
            [
                'title' => 'Craft Supplies',
                'image_file' => 'craft-supplies.png',
                'gradient_from' => 'fuchsia-200',
                'gradient_via' => 'pink-100',
                'gradient_to' => 'white',
                'is_large' => false,
                'order' => 3,
            ],
            [
                'title' => 'Party Setup',
                'image_file' => 'party-setup.jpg',
                'gradient_from' => 'amber-200',
                'gradient_via' => 'orange-100',
                'gradient_to' => 'white',
                'is_large' => false,
                'order' => 4,
            ],
            [
                'title' => 'Floral Arrangements',
                'image_file' => 'floral-arrangement.jpg',
                'gradient_from' => 'rose-300',
                'gradient_via' => 'pink-200',
                'gradient_to' => 'white',
                'is_large' => true,
                'order' => 5,
            ],
            [
                'title' => 'Gift Wrap',
                'image_file' => 'gift-wrap.jpg',
                'gradient_from' => 'stone-200',
                'gradient_via' => 'stone-100',
                'gradient_to' => 'white',
                'is_large' => false,
                'order' => 6,
            ],
            [
                'title' => 'Table Decor',
                'image_file' => 'table-decor.jpg',
                'gradient_from' => 'emerald-200',
                'gradient_via' => 'teal-100',
                'gradient_to' => 'white',
                'is_large' => false,
                'order' => 7,
            ],
        ];

        foreach ($galleryItems as $item) {
            $imageFile = $item['image_file'];
            unset($item['image_file']);
            
            $image = $this->copyImage('gallery', $imageFile, 'gallery');
            
            GalleryItem::updateOrCreate(
                ['title' => $item['title']],
                array_merge($item, [
                    'image' => $image,
                    'is_active' => true,
                ])
            );
        }
        
        $this->command->info('  → Gallery items seeded');
    }

    protected function seedTestimonials(): void
    {
        $testimonials = [
            [
                'content' => "Mary's selection is unmatched! I've been a customer for 5 years and always find the perfect ribbons for my wedding invitations business.",
                'author_name' => 'Sarah Mitchell',
                'author_title' => 'Wedding Stationery Designer',
                'rating' => 5,
                'order' => 1,
            ],
            [
                'content' => "The quality of laces here is exceptional. I travel 2 hours just to visit this store because nothing else compares.",
                'author_name' => 'Emily Chen',
                'author_title' => 'Fashion Designer',
                'rating' => 5,
                'order' => 2,
            ],
            [
                'content' => "Best craft store in the area! Mary always helps me find exactly what I need, even when I don't know what I'm looking for.",
                'author_name' => 'Jennifer Adams',
                'author_title' => 'DIY Enthusiast',
                'rating' => 5,
                'order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['author_name' => $testimonial['author_name']],
                array_merge($testimonial, ['is_active' => true])
            );
        }
        
        $this->command->info('  → Testimonials seeded');
    }
}
