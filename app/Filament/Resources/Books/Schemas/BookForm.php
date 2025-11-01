<?php

namespace App\Filament\Resources\Books\Schemas;

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
            ]);
    }
}
