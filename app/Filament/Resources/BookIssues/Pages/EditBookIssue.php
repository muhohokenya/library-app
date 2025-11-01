<?php

namespace App\Filament\Resources\BookIssues\Pages;

use App\Filament\Resources\BookIssues\BookIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditBookIssue extends EditRecord
{
    protected static string $resource = BookIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Check if status changed
        if ($this->record->wasChanged('status')) {
            $oldStatus = $this->record->getOriginal('status');
            $newStatus = $this->record->status;

            // If book was just returned
            if ($oldStatus === 'issued' && $newStatus === 'returned') {
                // Increase available quantity
                $this->record->book->increment('available_quantity');

                // Set return date if not already set
                if (!$this->record->return_date) {
                    $this->record->update(['return_date' => now()]);
                }

                Notification::make()
                    ->success()
                    ->title('Book Returned')
                    ->body('Available quantity has been updated.')
                    ->send();
            }
        }
    }
}
