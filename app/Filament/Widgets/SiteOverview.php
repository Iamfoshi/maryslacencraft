<?php

namespace App\Filament\Widgets;

use App\Models\GalleryItem;
use App\Models\ProductCategory;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Product Categories', ProductCategory::active()->count())
                ->description('Active categories on site')
                ->descriptionIcon('heroicon-m-squares-plus')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('Gallery Items', GalleryItem::active()->count())
                ->description('Photos in gallery')
                ->descriptionIcon('heroicon-m-photo')
                ->color('success')
                ->chart([3, 5, 4, 6, 7, 4, 6]),

            Stat::make('Testimonials', Testimonial::active()->count())
                ->description('Customer reviews')
                ->descriptionIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->color('warning')
                ->chart([2, 4, 3, 5, 4, 6, 5]),
        ];
    }
}

