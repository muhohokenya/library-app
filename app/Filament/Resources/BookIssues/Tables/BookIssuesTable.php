<?php

namespace App\Filament\Resources\BookIssues\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class BookIssuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Issue ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('member.member_id')
                    ->label('Member ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('member.full_name')
                    ->label('Member Name')
                    ->searchable(['first_name', 'last_name']),

                TextColumn::make('book.title')
                    ->label('Book Title')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('book.available_quantity')
                    ->label('Available')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger'),

                TextColumn::make('issue_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('return_date')
                    ->date()
                    ->placeholder('Not returned')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'issued' => 'info',
                        'returned' => 'success',
                        'overdue' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),


                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'issued' => 'Issued',
                        'returned' => 'Returned',
                        'overdue' => 'Overdue',
                    ]),
            ])
            ->recordActions([
                Action::make('return')
                    ->label('Return Book')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Return Book')
                    ->modalDescription('Are you sure you want to mark this book as returned?')
                    ->visible(fn ($record) => $record->status === 'issued' || $record->status === 'overdue')
                    ->action(function ($record) {
                        // Update the record
                        $record->update([
                            'return_date' => now(),
                            'status' => 'returned',
                        ]);

                        // Increase available quantity
                        $record->book->increment('available_quantity');

                        // Show success notification
                        Notification::make()
                            ->success()
                            ->title('Book Returned Successfully')
                            ->body("'{$record->book->title}' has been returned.")
                            ->send();
                    }),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    ->visible(fn () => auth()->user()->can('delete books'))
                    ,
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
