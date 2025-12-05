<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutSectionResource\Pages;
use App\Models\AboutSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AboutSectionResource extends Resource
{
    protected static ?string $model = AboutSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationGroup = 'Page Sections';

    protected static ?string $navigationLabel = 'About Section';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title_line1';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('About Content')
                    ->description('Tell your story to visitors')
                    ->schema([
                        Forms\Components\TextInput::make('badge_text')
                            ->label('Section Badge')
                            ->maxLength(50)
                            ->placeholder('e.g., Our Story'),
                        Forms\Components\TextInput::make('title_line1')
                            ->label('Title Line 1')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Crafted with Love,'),
                        Forms\Components\TextInput::make('title_line2')
                            ->label('Title Line 2 (Highlighted)')
                            ->maxLength(255)
                            ->placeholder('e.g., Curated with Care'),
                        Forms\Components\Textarea::make('lead_paragraph')
                            ->label('Lead Paragraph')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Opening sentence that captures attention...')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->label('Main Content')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                            ])
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Image & Stats')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('about')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('4:5')
                            ->deletable(true)
                            ->openable()
                            ->downloadable(),
                        Forms\Components\TextInput::make('stat_number')
                            ->label('Stat Number')
                            ->maxLength(20)
                            ->placeholder('e.g., 14+'),
                        Forms\Components\TextInput::make('stat_label')
                            ->label('Stat Label')
                            ->maxLength(50)
                            ->placeholder('e.g., Years of Passion'),
                    ])->columns(3),

                Forms\Components\Section::make('Feature Highlights')
                    ->description('Two feature boxes shown below the content')
                    ->schema([
                        Forms\Components\TextInput::make('feature1_title')
                            ->label('Feature 1 Title')
                            ->maxLength(100)
                            ->placeholder('e.g., Premium Quality'),
                        Forms\Components\TextInput::make('feature1_description')
                            ->label('Feature 1 Description')
                            ->maxLength(100)
                            ->placeholder('e.g., Hand-selected materials'),
                        Forms\Components\TextInput::make('feature2_title')
                            ->label('Feature 2 Title')
                            ->maxLength(100)
                            ->placeholder('e.g., Unique Selection'),
                        Forms\Components\TextInput::make('feature2_description')
                            ->label('Feature 2 Description')
                            ->maxLength(100)
                            ->placeholder('e.g., Rare finds & classics'),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=About&background=E8B4B8&color=2D2A26'),
                Tables\Columns\TextColumn::make('title_line1')
                    ->label('Title')
                    ->searchable()
                    ->description(fn ($record) => $record->title_line2)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('stat_number')
                    ->label('Stat')
                    ->badge()
                    ->color('primary')
                    ->suffix(fn ($record) => ' ' . $record->stat_label),
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
            'index' => Pages\ListAboutSections::route('/'),
            'create' => Pages\CreateAboutSection::route('/create'),
            'edit' => Pages\EditAboutSection::route('/{record}/edit'),
        ];
    }
}
