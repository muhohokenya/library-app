<?php

namespace App\Filament\Resources\BookIssues\Pages;

use App\Filament\Resources\BookIssues\BookIssueResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBookIssue extends ViewRecord
{
    protected static string $resource = BookIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
