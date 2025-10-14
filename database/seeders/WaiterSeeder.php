<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WaiterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('waiters')->insert([
            ['name' => 'John Doe', 'contact' => '9876543210'],
            ['name' => 'Emma Smith', 'contact' => '9123456789'],
            ['name' => 'Liam Johnson', 'contact' => '9988776655'],
            ['name' => 'Olivia Brown', 'contact' => '9090909090'],
            ['name' => 'Noah Davis', 'contact' => '9012345678'],
        ]);
    }
}
