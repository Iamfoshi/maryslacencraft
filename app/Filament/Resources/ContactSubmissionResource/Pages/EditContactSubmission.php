<?php

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Filament\Resources\ContactSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactSubmission extends EditRecord
{
    protected static string $resource = ContactSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reply')
                ->label('Reply via Email')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->url(fn () => "mailto:{$this->record->email}?subject=Re: {$this->record->subject}")
                ->openUrlInNewTab(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

