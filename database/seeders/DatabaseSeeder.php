<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);

        $user = User::firstOrCreate([
            'email' => 'user@example.com',
        ], [
            'name' => 'Normal User',
            'password' => bcrypt('password'),
        ]);

//        $this->call(RoleSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(WaiterSeeder::class);
        $this->call(TableSeeder::class);
        $this->call(RiderSeeder::class);
    }
}
