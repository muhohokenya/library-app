<?php

namespace App\Console\Commands;

use App\Models\BookIssue;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOverdueBooks extends Command
{
    protected $signature = 'books:check-overdue';
    protected $description = 'Check for overdue books and create fines';

    public function handle(): int
    {
        $this->info('Checking for overdue books...');
        $this->info('Current date: ' . Carbon::today()->toDateString());

        Log::info("Starting overdue books check");

        // Get all issued books that are past due date (including books due today)
        $overdueIssues = BookIssue::query()
            ->where('status', 'issued')
            ->whereDate('due_date', '<=', Carbon::today()) // Changed from '<' to '<='
            ->whereDoesntHave('fine')
            ->get();

        $this->info("Found {$overdueIssues->count()} overdue books");
        Log::info("Found {$overdueIssues->count()} overdue books");

        $finesCreated = 0;

        foreach ($overdueIssues as $issue) {
            $daysOverdue = Carbon::parse($issue->due_date)->diffInDays(Carbon::today());

            // Only create fine if actually overdue (at least 1 day)
            if ($daysOverdue < 1) {
                $this->info("Issue #{$issue->id} is due today, skipping fine creation");
                continue;
            }

            $fineAmount = $daysOverdue * 10; // KES 10 per day

            Fine::query()->create([
                'book_issue_id' => $issue->id,
                'member_id' => $issue->member_id,
                'amount' => $fineAmount,
                'days_overdue' => $daysOverdue,
            ]);

            $finesCreated++;
            $this->info("Fine created for Issue #{$issue->id} - KES {$fineAmount} ({$daysOverdue} days overdue)");
            Log::info("Fine created for Issue #{$issue->id} - KES {$fineAmount} ({$daysOverdue} days overdue)");
        }

        if ($finesCreated > 0) {
            $this->info("Total fines created: {$finesCreated}");
            Log::info("Total fines created: {$finesCreated}");
        } else {
            $this->info("No new fines created");
            Log::info("No new fines created");
        }

        return 0;
    }
}
