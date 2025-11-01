<?php

namespace App\Filament\Resources\BookIssues;

use App\Filament\Resources\BookIssues\Pages\CreateBookIssue;
use App\Filament\Resources\BookIssues\Pages\EditBookIssue;
use App\Filament\Resources\BookIssues\Pages\ListBookIssues;
use App\Filament\Resources\BookIssues\Pages\ViewBookIssue;
use App\Filament\Resources\BookIssues\Schemas\BookIssueForm;
use App\Filament\Resources\BookIssues\Schemas\BookIssueInfolist;
use App\Filament\Resources\BookIssues\Tables\BookIssuesTable;
use App\Models\BookIssue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookIssueResource extends Resource
{
    protected static ?string $model = BookIssue::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowRightCircle;

    protected static ?string $navigationLabel = 'Book Issues';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return BookIssueForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BookIssueInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookIssuesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookIssues::route('/'),
            'create' => CreateBookIssue::route('/create'),
            'view' => ViewBookIssue::route('/{record}'),
            'edit' => EditBookIssue::route('/{record}/edit'),
        ];
    }
}
