<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Fine;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookWithFinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating test data for overdue books and fines...');

        // Get or create test members
        $members = Member::limit(3)->get();
        if ($members->count() < 3) {
            $this->command->warn('Not enough members found. Creating test members...');
            $members = collect([]);
            for ($i = 0; $i < 3; $i++) {
                $members->push(Member::create([
                    'member_id' => 'TEST' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'first_name' => 'Test',
                    'last_name' => 'Member ' . ($i + 1),
                    'email' => 'testmember' . ($i + 1) . '@example.com',
                    'phone' => '0700000' . ($i + 1) . '00',
                    'member_type' => 'student',
                    'status' => 'active',
                    'membership_start' => Carbon::now()->subMonths(6),
                ]));
            }
        }

        // Get or create test books
        $books = Book::limit(5)->get();
        if ($books->count() < 5) {
            $this->command->warn('Not enough books found. Creating test books...');
            $books = collect([]);
            for ($i = 0; $i < 5; $i++) {
                $books->push(Book::create([
                    'title' => 'Test Book ' . ($i + 1),
                    'author' => 'Test Author ' . ($i + 1),
                    'isbn' => 'TEST-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                    'shelf_location' => 'A' . ($i + 1) . '-' . ($i + 1),
                    'quantity' => 3,
                    'available_quantity' => 2,
                    'book_status' => 'available',
                    'publisher' => 'Test Publisher',
                    'published_year' => 2020 + $i,
                ]));
            }
        }

        // Scenario 1: Book overdue by 5 days - NO fine yet (to test cron job)
        $issue1 = BookIssue::create([
            'book_id' => $books[0]->id,
            'member_id' => $members[0]->id,
            'issue_date' => Carbon::now()->subDays(12),
            'due_date' => Carbon::now()->subDays(5),
            'status' => 'issued',
        ]);
        $this->command->info("✓ Created overdue issue (5 days) WITHOUT fine: Issue #{$issue1->id}");

        // Scenario 2: Book overdue by 3 days - NO fine yet
        $issue2 = BookIssue::create([
            'book_id' => $books[1]->id,
            'member_id' => $members[1]->id,
            'issue_date' => Carbon::now()->subDays(10),
            'due_date' => Carbon::now()->subDays(3),
            'status' => 'issued',
        ]);
        $this->command->info("✓ Created overdue issue (3 days) WITHOUT fine: Issue #{$issue2->id}");

        // Scenario 3: Book overdue by 10 days - WITH fine already created
        $issue3 = BookIssue::create([
            'book_id' => $books[2]->id,
            'member_id' => $members[2]->id,
            'issue_date' => Carbon::now()->subDays(17),
            'due_date' => Carbon::now()->subDays(10),
            'status' => 'overdue',
        ]);
        $fine3 = Fine::create([
            'book_issue_id' => $issue3->id,
            'member_id' => $members[2]->id,
            'amount' => 100.00, // 10 days * KES 10
            'days_overdue' => 10,
        ]);
        $this->command->info("✓ Created overdue issue (10 days) WITH fine: Issue #{$issue3->id}, Fine #{$fine3->id}");

        // Scenario 4: Book just returned - was overdue with fine
        $issue4 = BookIssue::create([
            'book_id' => $books[3]->id,
            'member_id' => $members[0]->id,
            'issue_date' => Carbon::now()->subDays(14),
            'due_date' => Carbon::now()->subDays(7),
            'return_date' => Carbon::now()->subDays(1),
            'status' => 'returned',
        ]);
        $fine4 = Fine::create([
            'book_issue_id' => $issue4->id,
            'member_id' => $members[0]->id,
            'amount' => 60.00, // 6 days * KES 10
            'days_overdue' => 6,
        ]);
        $this->command->info("✓ Created returned book with fine: Issue #{$issue4->id}, Fine #{$fine4->id}");

        // Scenario 5: Book overdue by 1 day - NO fine yet
        $issue5 = BookIssue::create([
            'book_id' => $books[4]->id,
            'member_id' => $members[1]->id,
            'issue_date' => Carbon::now()->subDays(8),
            'due_date' => Carbon::now()->subDays(1),
            'status' => 'issued',
        ]);
        $this->command->info("✓ Created overdue issue (1 day) WITHOUT fine: Issue #{$issue5->id}");

        // Scenario 6: Book due today - should NOT create fine
        $issue6 = BookIssue::create([
            'book_id' => $books[0]->id,
            'member_id' => $members[2]->id,
            'issue_date' => Carbon::now()->subDays(7),
            'due_date' => Carbon::now(),
            'status' => 'issued',
        ]);
        $this->command->info("✓ Created issue due today: Issue #{$issue6->id}");

        // Scenario 7: Book due tomorrow - should NOT create fine
        $issue7 = BookIssue::create([
            'book_id' => $books[1]->id,
            'member_id' => $members[0]->id,
            'issue_date' => Carbon::now()->subDays(6),
            'due_date' => Carbon::now()->addDay(),
            'status' => 'issued',
        ]);
        $this->command->info("✓ Created issue due tomorrow: Issue #{$issue7->id}");

        $this->command->newLine();
        $this->command->info('✅ Test data created successfully!');
        $this->command->newLine();
        $this->command->table(
            ['Scenario', 'Issue ID', 'Due Date', 'Days Overdue', 'Has Fine', 'Expected Result'],
            [
                ['Overdue 5 days', $issue1->id, $issue1->due_date->format('Y-m-d'), '5', 'No', '✓ Create KES 50 fine'],
                ['Overdue 3 days', $issue2->id, $issue2->due_date->format('Y-m-d'), '3', 'No', '✓ Create KES 30 fine'],
                ['Overdue 10 days', $issue3->id, $issue3->due_date->format('Y-m-d'), '10', 'Yes', '✗ Skip (has fine)'],
                ['Returned', $issue4->id, $issue4->due_date->format('Y-m-d'), '6', 'Yes', '✗ Skip (returned)'],
                ['Overdue 1 day', $issue5->id, $issue5->due_date->format('Y-m-d'), '1', 'No', '✓ Create KES 10 fine'],
                ['Due today', $issue6->id, $issue6->due_date->format('Y-m-d'), '0', 'No', '✗ Skip (not overdue)'],
                ['Due tomorrow', $issue7->id, $issue7->due_date->format('Y-m-d'), '-1', 'No', '✗ Skip (future)'],
            ]
        );
        $this->command->newLine();
        $this->command->line('Current fines in database: ' . Fine::count());
        $this->command->warn('Expected fines after cron: ' . (Fine::count() + 3));
        $this->command->newLine();
        $this->command->info('📋 To test the cron job, run:');
        $this->command->line('   php artisan books:check-overdue');
        $this->command->newLine();
        $this->command->info('📊 To verify results, run:');
        $this->command->line('   php artisan tinker');
        $this->command->line('   >>> Fine::with("bookIssue.book", "member")->latest()->get()');
    }
}
