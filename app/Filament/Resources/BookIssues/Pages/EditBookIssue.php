<?php

namespace App\Filament\Resources\BookIssues\Pages;

use App\Filament\Resources\BookIssues\BookIssueResource;
use App\Models\Fine;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

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
        if ($this->record->status === 'returned' && !$this->record->fine) {
            $dueDate = \Carbon\Carbon::parse($this->record->due_date);
            $returnDate = \Carbon\Carbon::parse($this->record->return_date ?? now());

            if ($returnDate->gt($dueDate)) {
                $daysLate = $dueDate->diffInDays($returnDate);

                \App\Models\Fine::create([
                    'book_issue_id' => $this->record->id,
                    'member_id' => $this->record->member_id,
                    'amount' => $daysLate * 10,
                    'days_overdue' => $daysLate,
                ]);
            }
        }
    }
}
