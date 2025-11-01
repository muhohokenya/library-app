<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalBooks = Book::query()->sum('quantity');
        $availableBooks = Book::query()->sum('available_quantity');
        $issuedBooks = BookIssue::query()->where('status', 'issued')->count();
        $overdueBooks = BookIssue::query()->where('status', 'issued')
            ->where('due_date', '<', now())
            ->count();
        $totalMembers = Member::count();
        $activeMembers = Member::whereHas('activeIssues')->count();

        return [
            Stat::make('Total Books', $totalBooks)
                ->description('Total books in library')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('success'),

            Stat::make('Available Books', $availableBooks)
                ->description('Ready to be issued')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('info'),

            Stat::make('Books Issued', $issuedBooks)
                ->description("{$overdueBooks} overdue")
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color($overdueBooks > 0 ? 'warning' : 'success'),

            Stat::make('Total Members', $totalMembers)
                ->description("{$activeMembers} active borrowers")
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
