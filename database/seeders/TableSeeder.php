<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tables')->insert([
            ['no' => 'Table No 1', 'serial_no' => 'T1'],
            ['no' => 'Table No 2', 'serial_no' => 'T2'],
            ['no' => 'Table No 3', 'serial_no' => 'T3'],
            ['no' => 'Table No 4', 'serial_no' => 'T4'],
            ['no' => 'Table No 5', 'serial_no' => 'T5'],
        ]);
    }
}
