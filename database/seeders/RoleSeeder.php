<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $admin = Role::create(['name' => 'admin']);
        $librarian = Role::create(['name' => 'librarian']);

        // Create permissions
        $permissions = [
            'view books',
            'create books',
            'edit books',
            'delete books',
            'view members',
            'create members',
            'edit members',
            'delete members',
            'view issues',
            'create issues',
            'edit issues',
            'delete issues',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Admin gets all permissions
        $admin->givePermissionTo(Permission::all());

        // Librarian gets limited permissions
        $librarian->givePermissionTo([
            'view books',
            'view members',
            'create members',
            'edit members',
            'view issues',
            'create issues',
            'edit issues',
        ]);
    }
}
