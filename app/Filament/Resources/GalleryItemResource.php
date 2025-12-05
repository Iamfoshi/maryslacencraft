<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryItemResource\Pages;
use App\Models\GalleryItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Gallery';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Gallery Item')
                    ->description('Add images showcasing customer creations and inspiration')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Wedding Decor, Baby Shower'),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('gallery')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->deletable(true)
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Appearance')
                    ->description('Customize gradient colors (used as fallback if no image)')
                    ->schema([
                        Forms\Components\Select::make('gradient_from')
                            ->label('Gradient Start')
                            ->options([
                                'pink-200' => 'Pink',
                                'pink-300' => 'Pink Dark',
                                'rose-200' => 'Rose',
                                'rose-300' => 'Rose Dark',
                                'fuchsia-200' => 'Fuchsia',
                                'amber-200' => 'Amber',
                                'orange-200' => 'Orange',
                                'sky-200' => 'Sky Blue',
                                'stone-200' => 'Stone',
                            ])
                            ->default('pink-200'),
                        Forms\Components\Select::make('gradient_via')
                            ->label('Gradient Middle')
                            ->options([
                                'rose-100' => 'Rose Light',
                                'pink-100' => 'Pink Light',
                                'orange-100' => 'Orange Light',
                                'blue-100' => 'Blue Light',
                                'stone-100' => 'Stone Light',
                            ])
                            ->default('rose-100'),
                        Forms\Components\Select::make('gradient_to')
                            ->label('Gradient End')
                            ->options([
                                'white' => 'White',
                                'pink-50' => 'Pink Lightest',
                                'rose-50' => 'Rose Lightest',
                            ])
                            ->default('white'),
                    ])->columns(3),

                Forms\Components\Section::make('Layout & Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_large')
                            ->label('Large Card')
                            ->helperText('Display as a larger featured item'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
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
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->title) . '&background=E8B4B8&color=2D2A26'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\IconColumn::make('is_large')
                    ->boolean()
                    ->label('Featured'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('is_large')
                    ->label('Featured Items'),
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
            'index' => Pages\ListGalleryItems::route('/'),
            'create' => Pages\CreateGalleryItem::route('/create'),
            'edit' => Pages\EditGalleryItem::route('/{record}/edit'),
        ];
    }
}
