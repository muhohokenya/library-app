<?php

namespace App\Filament\Resources\BookIssues\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Book;
use App\Models\Member;

class BookIssueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('member_id')
                    ->label('Member')
                    ->relationship('member', 'member_id')
                    ->getOptionLabelFromRecordUsing(fn (Member $record) => "{$record->member_id} - {$record->full_name}")
                    ->searchable(['member_id', 'first_name', 'last_name'])
                    ->required()
                    ->preload(),

                Select::make('book_id')
                    ->label('Book')
                    ->relationship('book', 'title')
                    ->getOptionLabelFromRecordUsing(fn (Book $record) => "{$record->title} (Available: {$record->available_quantity})")
                    ->searchable(['title', 'author', 'isbn'])
                    ->required()
                    ->preload(),

                DatePicker::make('issue_date')
                    ->required()
                    ->default(now())
                    ->minDate(now()->startOfDay())
                    ->readOnly()
                    ->disabled()
                    ->native(false),

                DatePicker::make('due_date')
                    ->required()
                    ->default(now()->addDays(14))
                    ->minDate(now())
                    ->native(false),

                DatePicker::make('return_date')
                    ->native(false)
                    ->hidden(fn ($operation) => $operation === 'create'),

                Select::make('status')
                    ->options([
                        'issued' => 'Issued',
                        'returned' => 'Returned',
                        'overdue' => 'Overdue',
                    ])
                    ->required()
                    ->default('issued')
                    ->hidden(fn ($operation) => $operation === 'create'),

                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
