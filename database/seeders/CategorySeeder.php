<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Appetizer', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Soup', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Salad', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Continental', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Chinese & Thai', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Beef', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Sea Food', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Pasta', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Sandwich', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Burger', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Pizzas', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Dessert', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Soft Drinks', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Smoothie', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Groovy Shake', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Mocktail', 'created_at'=> now(), 'updated_at' => now()],
            ['name' => 'Cakes', 'created_at'=> now(), 'updated_at' => now()],
        ]);
    }
}
