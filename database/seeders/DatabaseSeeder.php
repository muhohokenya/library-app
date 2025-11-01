<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Run role seeder first
        $this->call(RoleSeeder::class);

        // Create admin user
        $admin = User::query()->create([
            'name' => 'Admin User',
            'email' => 'admin@library.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Create librarian user
        $librarian = User::query()->create([
            'name' => 'Librarian User',
            'email' => 'librarian@library.com',
            'password' => bcrypt('password'),
        ]);
        $librarian->assignRole('librarian');
    }
}
