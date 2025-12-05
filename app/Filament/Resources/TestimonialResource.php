<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Testimonials';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'author_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Review')
                    ->description('Add customer testimonials to build trust')
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label('Testimonial')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('What did the customer say about your products/service?')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('rating')
                            ->options([
                                5 => '⭐⭐⭐⭐⭐ (5 Stars)',
                                4 => '⭐⭐⭐⭐ (4 Stars)',
                                3 => '⭐⭐⭐ (3 Stars)',
                                2 => '⭐⭐ (2 Stars)',
                                1 => '⭐ (1 Star)',
                            ])
                            ->default(5),
                    ]),

                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('author_name')
                            ->label('Customer Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Sarah Mitchell'),
                        Forms\Components\TextInput::make('author_title')
                            ->label('Title / Profession')
                            ->maxLength(255)
                            ->placeholder('e.g., Wedding Planner, DIY Enthusiast'),
                        Forms\Components\FileUpload::make('author_image')
                            ->label('Photo (Optional)')
                            ->image()
                            ->avatar()
                            ->directory('testimonials')
                            ->deletable(true)
                            ->openable()
                            ->circleCropper(),
                    ])->columns(3),

                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Show on Website')
                            ->default(true),
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
                Tables\Columns\ImageColumn::make('author_image')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->author_name) . '&background=E8B4B8&color=2D2A26'),
                Tables\Columns\TextColumn::make('author_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('author_title')
                    ->label('Title')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('content')
                    ->label('Review')
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\TextColumn::make('rating')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state)),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        5 => '5 Stars',
                        4 => '4 Stars',
                        3 => '3 Stars',
                        2 => '2 Stars',
                        1 => '1 Star',
                    ]),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
