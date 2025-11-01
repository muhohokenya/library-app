<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Create 400 dummy books
        Book::factory()->count(400)->create();

        $this->command->info('Created 400 books successfully!');
    }
}
