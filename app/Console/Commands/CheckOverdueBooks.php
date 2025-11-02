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

        Log::write("info","Test Cron Job");

        // Get all issued books that are past due date by 24+ hours
        $overdueIssues = BookIssue::where('status', 'issued')
            ->whereDate('due_date', '<', Carbon::now()->subDay())
            ->whereDoesntHave('fine') // Only books without existing fines
            ->get();

        $finesCreated = 0;

        foreach ($overdueIssues as $issue) {
            $daysOverdue = Carbon::parse($issue->due_date)->diffInDays(Carbon::now());
            $fineAmount = $daysOverdue * 10; // KES 10 per day

            Fine::create([
                'book_issue_id' => $issue->id,
                'member_id' => $issue->member_id,
                'amount' => $fineAmount,
                'days_overdue' => $daysOverdue,
            ]);

            $finesCreated++;
            $this->info("Fine created for Issue #{$issue->id} - KES {$fineAmount}");
        }

        $this->info("Total fines created: {$finesCreated}");

        return 0;
    }
}
