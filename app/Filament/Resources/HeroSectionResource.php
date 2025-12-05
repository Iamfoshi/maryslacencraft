<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSectionResource\Pages;
use App\Models\HeroSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroSectionResource extends Resource
{
    protected static ?string $model = HeroSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Page Sections';

    protected static ?string $navigationLabel = 'Hero Section';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title_line1';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hero Content')
                    ->description('The main banner section visitors see first')
                    ->schema([
                        Forms\Components\TextInput::make('badge_text')
                            ->label('Badge Text')
                            ->maxLength(100)
                            ->placeholder('e.g., Wholesale & Retail Craft Supplies')
                            ->helperText('Small text shown above the title'),
                        Forms\Components\TextInput::make('title_line1')
                            ->label('Title Line 1')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Where Creativity'),
                        Forms\Components\TextInput::make('title_line2')
                            ->label('Title Line 2 (Highlighted)')
                            ->maxLength(255)
                            ->placeholder('e.g., Blossoms')
                            ->helperText('This line appears in accent color'),
                        Forms\Components\Textarea::make('subtitle')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Your one-stop shop for wholesale and retail craft supplies...')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Call to Action Buttons')
                    ->schema([
                        Forms\Components\TextInput::make('primary_button_text')
                            ->label('Primary Button Text')
                            ->maxLength(50)
                            ->placeholder('e.g., Explore Collection'),
                        Forms\Components\TextInput::make('primary_button_link')
                            ->label('Primary Button Link')
                            ->maxLength(255)
                            ->placeholder('e.g., #products'),
                        Forms\Components\TextInput::make('secondary_button_text')
                            ->label('Secondary Button Text')
                            ->maxLength(50)
                            ->placeholder('e.g., Visit Our Store'),
                        Forms\Components\TextInput::make('secondary_button_link')
                            ->label('Secondary Button Link')
                            ->maxLength(255)
                            ->placeholder('e.g., #contact'),
                    ])->columns(2),

                Forms\Components\Section::make('Background')
                    ->schema([
                        Forms\Components\FileUpload::make('background_image')
                            ->image()
                            ->directory('hero')
                            ->imageResizeMode('cover')
                            ->helperText('Optional background image for the hero section')
                            ->deletable(true)
                            ->openable()
                            ->downloadable(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only one hero section should be active'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('background_image')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=Hero&background=E8B4B8&color=2D2A26'),
                Tables\Columns\TextColumn::make('title_line1')
                    ->label('Title')
                    ->searchable()
                    ->description(fn ($record) => $record->title_line2)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('badge_text')
                    ->label('Badge')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListHeroSections::route('/'),
            'create' => Pages\CreateHeroSection::route('/create'),
            'edit' => Pages\EditHeroSection::route('/{record}/edit'),
        ];
    }
}
