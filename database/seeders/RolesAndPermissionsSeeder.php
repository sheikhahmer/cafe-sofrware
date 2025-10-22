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

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $staffRole = Role::firstOrCreate(['name' => 'User']);
        $adminRole->givePermissionTo(Permission::all());

        $staffPermissions = Permission::whereIn('name', [
            'view orders',
            'view products',
            'view item sales',
            'view order sales',
            'create orders',
        ])->get();

        $staffRole->givePermissionTo($staffPermissions);
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole($adminRole);
        }
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
