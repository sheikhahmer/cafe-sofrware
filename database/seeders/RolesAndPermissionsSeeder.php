<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 🔹 Define all permissions for your modules
        $modules = [
            'orders',
            'order items',
            'categories',
            'products',
            'expenses',
            'tables',
            'riders',
            'waiters',
            'item sales',
            'order sales',
            'roles'
        ];

        // Create permissions for all modules
        foreach ($modules as $module) {
            Permission::firstOrCreate(['name' => "view {$module}"]);
            Permission::firstOrCreate(['name' => "create {$module}"]);
            Permission::firstOrCreate(['name' => "edit {$module}"]);
            Permission::firstOrCreate(['name' => "delete {$module}"]);
        }

        // 🔹 Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // 🔹 Give all permissions to Admin
        $adminRole->givePermissionTo(Permission::all());

        // 🔹 Assign permissions for creating orders and expenses to User
        $userPermissions = Permission::whereIn('name', [
            'create orders',
            'create order items',
            'create expenses',
        ])->get();

        // Assign the permissions to the User role
        $userRole->givePermissionTo($userPermissions);

        // 🔹 Assign roles to users based on email
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole($adminRole); // Assign admin role to admin@example.com
        }

        $user = User::where('email', 'user@example.com')->first();
        if ($user) {
            $user->assignRole($userRole); // Assign user role to user@example.com
        }

        // Refresh cache again
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
