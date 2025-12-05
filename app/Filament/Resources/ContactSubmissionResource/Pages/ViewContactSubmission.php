<?php

namespace App\Filament\Resources\ContactSubmissionResource\Pages;

use App\Filament\Resources\ContactSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactSubmission extends ViewRecord
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
            Actions\EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Mark as read when viewing
        if ($this->record->status === 'new') {
            $this->record->markAsRead();
        }
        
        return $data;
    }
}

