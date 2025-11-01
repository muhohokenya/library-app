<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        // Create 100 members with different types
        Member::factory()->count(60)->create(['member_type' => 'student']);
        Member::factory()->count(20)->create(['member_type' => 'teacher']);
        Member::factory()->count(10)->create(['member_type' => 'staff']);
        Member::factory()->count(10)->create(['member_type' => 'public']);

        $this->command->info('Created 100 members successfully!');
    }
}
