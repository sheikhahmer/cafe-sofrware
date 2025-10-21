<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 🔹 Define all permissions for your modules
        $modules = [
            'orders',
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

        foreach ($modules as $module) {
            Permission::firstOrCreate(['name' => "view {$module}"]);
            Permission::firstOrCreate(['name' => "create {$module}"]);
            Permission::firstOrCreate(['name' => "edit {$module}"]);
            Permission::firstOrCreate(['name' => "delete {$module}"]);
        }

        // 🔹 Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $staffRole = Role::firstOrCreate(['name' => 'User']);

        // 🔹 Give full permissions to Admin
        $adminRole->givePermissionTo(Permission::all());

        // 🔹 Staff gets limited permissions
        $staffPermissions = Permission::whereIn('name', [
            'view orders',
            'view products',
            'view item sales',
            'view order sales',
            'create orders',
        ])->get();

        $staffRole->givePermissionTo($staffPermissions);

        // Refresh cache again
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
