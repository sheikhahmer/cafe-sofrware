<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Define your modules
        $modules = ['category', 'product', 'table', 'waiter', 'order'];

        // Define default actions for each module
        $actions = ['view', 'create', 'edit', 'delete'];

        // Create all permissions dynamically
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action} {$module}"]);
            }
        }

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Give admin all permissions
        $adminRole->syncPermissions(Permission::all());

        // Give user limited permissions (only view & create orders)
        $userPermissions = Permission::whereIn('name', [
            'view order',
            'create order',
            'view product',
        ])->get();

        $userRole->syncPermissions($userPermissions);

        // Optionally assign roles to existing users
        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            $admin->assignRole($adminRole);
        }

        $user = User::where('email', 'user@example.com')->first();
        if ($user) {
            $user->assignRole($userRole);
        }

        echo "✅ Roles & permissions seeded successfully.\n";
    }
}
