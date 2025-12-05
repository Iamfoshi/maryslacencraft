<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Navigation\NavigationGroup;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName("Mary's Lace n Craft")
            ->brandLogo(null)
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => [
                    50 => '#FDF8F6',
                    100 => '#F9EDE8',
                    200 => '#F5DDD4',
                    300 => '#E8B4B8',
                    400 => '#D4A5A8',
                    500 => '#C4927A',
                    600 => '#B07D68',
                    700 => '#96685A',
                    800 => '#7A564C',
                    900 => '#5C4640',
                    950 => '#2D2A26',
                ],
                'gray' => [
                    50 => '#FDFBF7',
                    100 => '#F5EDE4',
                    200 => '#E8DFD4',
                    300 => '#D4C4B0',
                    400 => '#B8A894',
                    500 => '#9C8C78',
                    600 => '#7A6E5C',
                    700 => '#5C5752',
                    800 => '#3D3A36',
                    900 => '#2D2A26',
                    950 => '#1a1815',
                ],
                'danger' => Color::Rose,
                'info' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
            ->font('Nunito Sans')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Website Content')
                    ->icon('heroicon-o-globe-alt'),
                NavigationGroup::make()
                    ->label('Page Sections')
                    ->icon('heroicon-o-squares-2x2'),
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
