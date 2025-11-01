<?php

namespace App\Filament\Widgets;

use App\Models\BookIssue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BookIssuesChart extends ChartWidget
{
    protected ?string $heading = 'Book Issues (Last 7 Days)';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'md' => 1,  // Half width (1 out of 2 columns)
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $data = $this->getIssuesPerDay();

        return [
            'datasets' => [
                [
                    'label' => 'Books Issued',
                    'data' => $data['counts'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function getIssuesPerDay(): array
    {
        $days = collect();
        $counts = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = BookIssue::whereDate('issue_date', $date)->count();

            $days->push($date->format('M d'));
            $counts->push($count);
        }

        return [
            'labels' => $days->toArray(),
            'counts' => $counts->toArray(),
        ];
    }
}
