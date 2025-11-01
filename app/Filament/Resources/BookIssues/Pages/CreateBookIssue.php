<?php

namespace App\Filament\Resources\BookIssues\Pages;

use App\Filament\Resources\BookIssues\BookIssueResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Book;
use Filament\Notifications\Notification;

class CreateBookIssue extends CreateRecord
{
    protected static string $resource = BookIssueResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Check if book is available before creating the issue
        $book = Book::find($data['book_id']);

        if ($book->available_quantity < 1) {

            Notification::make()
                ->danger()
                ->title('Book not available')
                ->body('This book has no available copies.')
                ->send();
            $this->halt();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Decrease available quantity after book is issued
        $this->record->book->decrement('available_quantity');

        Notification::make()
            ->success()
            ->title('Book Issued Successfully')
            ->body('Available quantity has been updated.')
            ->send();
    }
}
