<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Product Categories';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Category Information')
                    ->description('Basic details about the product category')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Ribbons, Laces & Trims'),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Brief description of this category'),
                        Forms\Components\TextInput::make('icon')
                            ->maxLength(50)
                            ->placeholder('e.g., 🎀 or heart')
                            ->helperText('Use an emoji or icon name'),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('categories')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->deletable(true)
                            ->openable()
                            ->downloadable()
                            ->previewable(true),
                    ])->columns(2),

                Forms\Components\Section::make('Appearance')
                    ->description('Customize the card gradient colors')
                    ->schema([
                        Forms\Components\Select::make('color_from')
                            ->label('Gradient Start Color')
                            ->options([
                                'pink-100' => 'Pink Light',
                                'pink-200' => 'Pink',
                                'rose-100' => 'Rose Light',
                                'rose-200' => 'Rose',
                                'amber-100' => 'Amber Light',
                                'amber-200' => 'Amber',
                                'orange-100' => 'Orange Light',
                                'stone-100' => 'Stone Light',
                                'stone-200' => 'Stone',
                            ])
                            ->default('pink-100'),
                        Forms\Components\Select::make('color_to')
                            ->label('Gradient End Color')
                            ->options([
                                'pink-50' => 'Pink Lightest',
                                'rose-50' => 'Rose Lightest',
                                'amber-50' => 'Amber Lightest',
                                'orange-50' => 'Orange Lightest',
                                'stone-50' => 'Stone Lightest',
                                'stone-100' => 'Stone Light',
                                'white' => 'White',
                            ])
                            ->default('pink-50'),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Show this category on the website'),
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('icon')
                    ->label(''),
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->title) . '&background=E8B4B8&color=2D2A26'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(40)
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order')
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}
