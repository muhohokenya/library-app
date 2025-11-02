<?php

namespace App\Filament\Resources\Fines;


use App\Filament\Resources\Fines\Pages\ManageFines;
use App\Models\Fine;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FineResource extends Resource
{
    protected static ?string $model = Fine::class;
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-banknotes';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Fine #')
                    ->sortable(),

                Tables\Columns\TextColumn::make('member.member_id')
                    ->label('Member ID')
                    ->searchable(),

                Tables\Columns\TextColumn::make('member.full_name')
                    ->label('Member Name')
                    ->getStateUsing(fn ($record) => $record->member->first_name . ' ' . $record->member->last_name)
                    ->searchable(),

                Tables\Columns\TextColumn::make('bookIssue.book.title')
                    ->label('Book')
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\TextColumn::make('days_overdue')
                    ->label('Days Overdue')
                    ->badge()
                    ->color('danger'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('KES')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created at')
                    ->date()
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFines::route('/'),
        ];
    }
}
