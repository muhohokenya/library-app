<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MostBorrowedBooks extends BaseWidget
{
    protected static ?int $sort = 2;  // Same sort as chart to be on same row

    protected int | string | array $columnSpan = [
        'md' => 1,  // Half width (1 out of 2 columns)
        'xl' => 1,
    ];

    protected static ?string $heading = 'Most Borrowed Books';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Book::query()
                    ->withCount('issues')
                    ->orderBy('issues_count', 'desc')
                    ->limit(5)  // Show only 5 books to fit better
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Book Title')
                    ->searchable()
                    ->limit(30)
                    ->wrap(),

                Tables\Columns\TextColumn::make('author')
                    ->label('Author')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('issues_count')
                    ->label('Borrowed')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('available_quantity')
                    ->label('Available')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'info' : 'danger'),
            ])
            ->paginated(false);
    }
}
