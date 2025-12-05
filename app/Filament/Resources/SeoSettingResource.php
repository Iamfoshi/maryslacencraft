<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoSettingResource\Pages;
use App\Models\SeoSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeoSettingResource extends Resource
{
    protected static ?string $model = SeoSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'SEO Settings';

    protected static ?int $navigationSort = 90;

    protected static ?string $recordTitleAttribute = 'meta_title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('SEO Settings')
                    ->tabs([
                        // Basic Meta Tags Tab
                        Forms\Components\Tabs\Tab::make('Meta Tags')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Section::make('Basic SEO')
                                    ->description('Core meta tags for search engines')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('Page Title')
                                            ->placeholder("Mary's Lace n Craft | Wholesale & Retail Craft Supplies")
                                            ->live()
                                            ->hint(fn ($state) => 
                                                strlen($state ?? '') . '/70 chars' . (strlen($state ?? '') > 70 ? ' ⚠️ May be truncated in search results' : ''))
                                            ->hintColor(fn ($state) => strlen($state ?? '') > 70 ? 'warning' : 'success')
                                            ->helperText('Recommended: 50-60 characters for best display in search results'),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->rows(3)
                                            ->placeholder('Your one-stop shop for wholesale and retail craft supplies in La Puente, CA. Ribbons, laces, flowers, baskets & party favors for all events.')
                                            ->live()
                                            ->hint(fn ($state) => 
                                                strlen($state ?? '') . '/160 chars' . (strlen($state ?? '') > 160 ? ' ⚠️ May be truncated in search results' : ''))
                                            ->hintColor(fn ($state) => strlen($state ?? '') > 160 ? 'warning' : 'success')
                                            ->helperText('Recommended: 150-160 characters for best display in search results'),
                                        Forms\Components\TagsInput::make('meta_keywords')
                                            ->label('Keywords')
                                            ->placeholder('Add keywords...')
                                            ->helperText('Add relevant keywords (optional, less important for modern SEO)'),
                                        Forms\Components\TextInput::make('canonical_url')
                                            ->label('Canonical URL')
                                            ->url()
                                            ->placeholder('https://maryslacencraft.com'),
                                    ])->columns(1),
                            ]),

                        // Open Graph Tab
                        Forms\Components\Tabs\Tab::make('Social Sharing')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Forms\Components\Section::make('Facebook / Open Graph')
                                    ->description('How your site appears when shared on Facebook and other platforms')
                                    ->schema([
                                        Forms\Components\TextInput::make('og_site_name')
                                            ->label('Site Name')
                                            ->placeholder("Mary's Lace n Craft"),
                                        Forms\Components\TextInput::make('og_title')
                                            ->label('OG Title')
                                            ->placeholder('Leave empty to use Meta Title')
                                            ->hint(fn ($state) => $state ? strlen($state) . '/70 chars' : '')
                                            ->hintColor(fn ($state) => strlen($state ?? '') > 70 ? 'warning' : 'gray'),
                                        Forms\Components\Textarea::make('og_description')
                                            ->label('OG Description')
                                            ->rows(2)
                                            ->placeholder('Leave empty to use Meta Description'),
                                        Forms\Components\FileUpload::make('og_image')
                                            ->label('Share Image')
                                            ->image()
                                            ->directory('seo')
                                            ->helperText('Recommended: 1200x630 pixels')
                                            ->deletable(true)
                                            ->openable()
                                            ->downloadable(),
                                        Forms\Components\Select::make('og_type')
                                            ->label('Content Type')
                                            ->options([
                                                'website' => 'Website',
                                                'article' => 'Article',
                                                'product' => 'Product',
                                                'local_business' => 'Local Business',
                                            ])
                                            ->default('website'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Twitter Card')
                                    ->description('How your site appears when shared on Twitter/X')
                                    ->schema([
                                        Forms\Components\Select::make('twitter_card')
                                            ->label('Card Type')
                                            ->options([
                                                'summary' => 'Summary',
                                                'summary_large_image' => 'Summary with Large Image',
                                            ])
                                            ->default('summary_large_image'),
                                        Forms\Components\TextInput::make('twitter_site')
                                            ->label('Twitter Handle')
                                            ->placeholder('@maryslacencraft')
                                            ->prefix('@'),
                                        Forms\Components\TextInput::make('twitter_title')
                                            ->label('Twitter Title')
                                            ->placeholder('Leave empty to use OG Title'),
                                        Forms\Components\Textarea::make('twitter_description')
                                            ->label('Twitter Description')
                                            ->rows(2)
                                            ->placeholder('Leave empty to use OG Description'),
                                        Forms\Components\FileUpload::make('twitter_image')
                                            ->label('Twitter Image')
                                            ->image()
                                            ->directory('seo')
                                            ->helperText('Leave empty to use OG Image')
                                            ->deletable(true)
                                            ->openable()
                                            ->downloadable(),
                                    ])->columns(2),
                            ]),

                        // Local Business Tab
                        Forms\Components\Tabs\Tab::make('Local Business')
                            ->icon('heroicon-o-building-storefront')
                            ->schema([
                                Forms\Components\Section::make('Business Information')
                                    ->description('Schema.org structured data for local business (helps with Google Maps & local search)')
                                    ->schema([
                                        Forms\Components\TextInput::make('business_name')
                                            ->label('Business Name')
                                            ->placeholder("Mary's Lace n Craft"),
                                        Forms\Components\Select::make('business_type')
                                            ->label('Business Type')
                                            ->options([
                                                'LocalBusiness' => 'Local Business',
                                                'Store' => 'Store',
                                                'RetailStore' => 'Retail Store',
                                                'CraftStore' => 'Craft Store',
                                                'HobbyShop' => 'Hobby Shop',
                                            ])
                                            ->default('Store'),
                                        Forms\Components\Textarea::make('business_description')
                                            ->label('Business Description')
                                            ->rows(3),
                                        Forms\Components\TextInput::make('business_phone')
                                            ->label('Phone')
                                            ->tel()
                                            ->placeholder('(626) 918-8511'),
                                        Forms\Components\TextInput::make('business_email')
                                            ->label('Email')
                                            ->email(),
                                        Forms\Components\Select::make('business_price_range')
                                            ->label('Price Range')
                                            ->options([
                                                '$' => '$ (Budget)',
                                                '$$' => '$$ (Moderate)',
                                                '$$$' => '$$$ (Expensive)',
                                                '$$$$' => '$$$$ (Luxury)',
                                            ])
                                            ->default('$$'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Address & Location')
                                    ->schema([
                                        Forms\Components\TextInput::make('business_address_street')
                                            ->label('Street Address')
                                            ->placeholder('1629 N Hacienda Blvd'),
                                        Forms\Components\TextInput::make('business_address_city')
                                            ->label('City')
                                            ->placeholder('La Puente'),
                                        Forms\Components\TextInput::make('business_address_state')
                                            ->label('State')
                                            ->placeholder('CA'),
                                        Forms\Components\TextInput::make('business_address_zip')
                                            ->label('ZIP Code')
                                            ->placeholder('91744'),
                                        Forms\Components\TextInput::make('business_address_country')
                                            ->label('Country')
                                            ->default('US'),
                                        Forms\Components\TextInput::make('business_latitude')
                                            ->label('Latitude')
                                            ->numeric()
                                            ->placeholder('34.0330'),
                                        Forms\Components\TextInput::make('business_longitude')
                                            ->label('Longitude')
                                            ->numeric()
                                            ->placeholder('-117.9497'),
                                    ])->columns(3),

                                Forms\Components\Section::make('Business Hours')
                                    ->schema([
                                        Forms\Components\Repeater::make('business_hours')
                                            ->label('Opening Hours')
                                            ->schema([
                                                Forms\Components\Select::make('dayOfWeek')
                                                    ->label('Day')
                                                    ->options([
                                                        'Monday' => 'Monday',
                                                        'Tuesday' => 'Tuesday',
                                                        'Wednesday' => 'Wednesday',
                                                        'Thursday' => 'Thursday',
                                                        'Friday' => 'Friday',
                                                        'Saturday' => 'Saturday',
                                                        'Sunday' => 'Sunday',
                                                    ])
                                                    ->required(),
                                                Forms\Components\TimePicker::make('opens')
                                                    ->label('Opens')
                                                    ->seconds(false),
                                                Forms\Components\TimePicker::make('closes')
                                                    ->label('Closes')
                                                    ->seconds(false),
                                            ])
                                            ->columns(3)
                                            ->defaultItems(0)
                                            ->collapsible(),
                                    ]),

                                Forms\Components\Section::make('Logo')
                                    ->schema([
                                        Forms\Components\FileUpload::make('business_logo')
                                            ->label('Business Logo')
                                            ->image()
                                            ->directory('seo')
                                            ->helperText('Used in structured data and search results')
                                            ->deletable(true)
                                            ->openable()
                                            ->downloadable(),
                                    ]),
                            ]),

                        // Social Links Tab
                        Forms\Components\Tabs\Tab::make('Social Links')
                            ->icon('heroicon-o-link')
                            ->schema([
                                Forms\Components\Section::make('Social Media Profiles')
                                    ->description('Links to your social media profiles (used in structured data)')
                                    ->schema([
                                        Forms\Components\TextInput::make('facebook_url')
                                            ->label('Facebook')
                                            ->url()
                                            ->placeholder('https://facebook.com/maryslacencraft'),
                                        Forms\Components\TextInput::make('instagram_url')
                                            ->label('Instagram')
                                            ->url()
                                            ->placeholder('https://instagram.com/maryslacencraft'),
                                        Forms\Components\TextInput::make('twitter_url')
                                            ->label('Twitter / X')
                                            ->url()
                                            ->placeholder('https://x.com/maryslacencraft'),
                                        Forms\Components\TextInput::make('pinterest_url')
                                            ->label('Pinterest')
                                            ->url()
                                            ->placeholder('https://pinterest.com/maryslacencraft'),
                                        Forms\Components\TextInput::make('yelp_url')
                                            ->label('Yelp')
                                            ->url()
                                            ->placeholder('https://yelp.com/biz/marys-lace-n-craft'),
                                        Forms\Components\TextInput::make('google_business_url')
                                            ->label('Google Business')
                                            ->url()
                                            ->placeholder('https://g.page/maryslacencraft'),
                                    ])->columns(2),
                            ]),

                        // Analytics & Tracking Tab
                        Forms\Components\Tabs\Tab::make('Analytics')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Forms\Components\Section::make('Google')
                                    ->schema([
                                        Forms\Components\TextInput::make('google_analytics_id')
                                            ->label('Google Analytics 4 ID')
                                            ->placeholder('G-XXXXXXXXXX'),
                                        Forms\Components\TextInput::make('google_tag_manager_id')
                                            ->label('Google Tag Manager ID')
                                            ->placeholder('GTM-XXXXXXX'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Facebook')
                                    ->schema([
                                        Forms\Components\TextInput::make('facebook_pixel_id')
                                            ->label('Facebook Pixel ID')
                                            ->placeholder('XXXXXXXXXXXXXXXX'),
                                    ]),

                                Forms\Components\Section::make('Custom Scripts')
                                    ->description('Add custom tracking scripts (be careful with this)')
                                    ->schema([
                                        Forms\Components\Textarea::make('custom_head_scripts')
                                            ->label('Head Scripts')
                                            ->rows(4)
                                            ->helperText('Scripts added before </head>'),
                                        Forms\Components\Textarea::make('custom_body_scripts')
                                            ->label('Body Scripts')
                                            ->rows(4)
                                            ->helperText('Scripts added before </body>'),
                                    ]),
                            ]),

                        // Advanced Tab
                        Forms\Components\Tabs\Tab::make('Advanced')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Section::make('Search Engine Verification')
                                    ->description('Verify your site ownership with search engines')
                                    ->schema([
                                        Forms\Components\TextInput::make('google_site_verification')
                                            ->label('Google Search Console')
                                            ->placeholder('Enter verification code (e.g., abc123xyz...)')
                                            ->helperText('Get this from Google Search Console → Settings → Ownership verification → HTML tag. Enter only the content value.'),
                                        Forms\Components\TextInput::make('bing_site_verification')
                                            ->label('Bing Webmaster Tools')
                                            ->placeholder('Enter verification code')
                                            ->helperText('Get this from Bing Webmaster Tools verification.'),
                                        Forms\Components\TextInput::make('pinterest_site_verification')
                                            ->label('Pinterest')
                                            ->placeholder('Enter verification code')
                                            ->helperText('Get this from Pinterest business account verification.'),
                                    ])->columns(1),

                                Forms\Components\Section::make('Robots.txt')
                                    ->description('Control how search engines crawl your site')
                                    ->schema([
                                        Forms\Components\Textarea::make('robots_txt')
                                            ->label('Custom robots.txt content')
                                            ->rows(10)
                                            ->placeholder("User-agent: *\nAllow: /\nSitemap: https://maryslacencraft.com/sitemap.xml")
                                            ->helperText('Leave empty to use default robots.txt'),
                                    ]),

                                Forms\Components\Section::make('Status')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true)
                                            ->helperText('Enable these SEO settings'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('meta_title')
                    ->label('Title')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('business_name')
                    ->label('Business')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Last Updated'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeoSettings::route('/'),
            'create' => Pages\CreateSeoSetting::route('/create'),
            'edit' => Pages\EditSeoSetting::route('/{record}/edit'),
        ];
    }
}
