<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageStoreHours extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?string $navigationLabel = 'Store Hours';
    
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.manage-store-hours';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'location_name' => SiteSetting::get('location_name', 'South Hill Square'),
            'address_line1' => SiteSetting::get('address_line1', 'South Hills Shopping Center'),
            'address_line2' => SiteSetting::get('address_line2', '1629 N Hacienda Blvd, La Puente, CA 91744'),
            'phone' => SiteSetting::get('phone', '(626) 918-8511'),
            'hours_monday' => SiteSetting::get('hours_monday', '9 AM – 7 PM'),
            'hours_tuesday' => SiteSetting::get('hours_tuesday', '9 AM – 7 PM'),
            'hours_wednesday' => SiteSetting::get('hours_wednesday', '9 AM – 7 PM'),
            'hours_thursday' => SiteSetting::get('hours_thursday', '9 AM – 7 PM'),
            'hours_friday' => SiteSetting::get('hours_friday', '9 AM – 7 PM'),
            'hours_saturday' => SiteSetting::get('hours_saturday', '10 AM – 6 PM'),
            'hours_sunday' => SiteSetting::get('hours_sunday', '12 PM – 5 PM'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Store Location')
                    ->description('Your store address and contact information')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Forms\Components\TextInput::make('location_name')
                            ->label('Location Name')
                            ->placeholder('e.g., South Hill Square')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address_line1')
                            ->label('Address Line 1')
                            ->placeholder('e.g., South Hills Shopping Center')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address_line2')
                            ->label('Address Line 2')
                            ->placeholder('e.g., 1629 N Hacienda Blvd, La Puente, CA 91744')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->placeholder('e.g., (626) 918-8511')
                            ->maxLength(50),
                    ])->columns(2),

                Forms\Components\Section::make('Hours')
                    ->description('Store operating hours by day of the week')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('hours_monday')
                                    ->label('Mon')
                                    ->placeholder('9 AM – 7 PM')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('hours_tuesday')
                                    ->label('Tue')
                                    ->placeholder('9 AM – 7 PM')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('hours_wednesday')
                                    ->label('Wed')
                                    ->placeholder('9 AM – 7 PM')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('hours_thursday')
                                    ->label('Thu')
                                    ->placeholder('9 AM – 7 PM')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('hours_friday')
                                    ->label('Fri')
                                    ->placeholder('9 AM – 7 PM')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('hours_saturday')
                                    ->label('Sat')
                                    ->placeholder('10 AM – 6 PM')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('hours_sunday')
                                    ->label('Sun')
                                    ->placeholder('12 PM – 5 PM')
                                    ->maxLength(50),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save location info
        SiteSetting::set('location_name', $data['location_name'], 'Location Name', 'contact');
        SiteSetting::set('address_line1', $data['address_line1'], 'Address Line 1', 'contact');
        SiteSetting::set('address_line2', $data['address_line2'], 'Address Line 2', 'contact');
        SiteSetting::set('phone', $data['phone'], 'Phone Number', 'contact');

        // Save hours
        SiteSetting::set('hours_monday', $data['hours_monday'], 'Monday Hours', 'hours');
        SiteSetting::set('hours_tuesday', $data['hours_tuesday'], 'Tuesday Hours', 'hours');
        SiteSetting::set('hours_wednesday', $data['hours_wednesday'], 'Wednesday Hours', 'hours');
        SiteSetting::set('hours_thursday', $data['hours_thursday'], 'Thursday Hours', 'hours');
        SiteSetting::set('hours_friday', $data['hours_friday'], 'Friday Hours', 'hours');
        SiteSetting::set('hours_saturday', $data['hours_saturday'], 'Saturday Hours', 'hours');
        SiteSetting::set('hours_sunday', $data['hours_sunday'], 'Sunday Hours', 'hours');

        Notification::make()
            ->title('Store hours saved!')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }
}

