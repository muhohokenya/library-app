<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('author')
                    ->required(),
                TextInput::make('isbn')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('available_quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('publisher'),
                TextInput::make('published_year')
                    ->numeric(),
                TextInput::make('shelf_location')
                    ->label('Shelf Location')
                    ->placeholder('e.g., A1, B2, Section-C-Row-3')
                    ->helperText('Physical location of the book in library'),

                Select::make('book_status')
                    ->label('Book Status')
                    ->options([
                        'available' => 'Available',
                        'issued' => 'Issued',
                        'missing' => 'Missing',
                        'damaged' => 'Damaged',
                    ])
                    ->default('available')
                    ->required(),
            ]);
    }
}
