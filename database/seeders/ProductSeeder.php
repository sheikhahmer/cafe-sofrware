<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Retrieve all categories
        $categories = Category::all();

        // Loop through categories and create products
        foreach ($categories as $category) {
            // Create 5 products for each category
            for ($i = 1; $i <= 5; $i++) {
                // Generate a product code based on the category abbreviation and product number
                $productCode = strtoupper(substr($category->name, 0, 2)) . '-' . $i;

                Product::create([
                    'name' => $category->name . ' Product ' . $i, // e.g., Kitchen Product 1
                    'price' => rand(10, 100), // Random price between 10 and 100
                    'description' => $productCode, // Use the generated product code
                    'category_id' => $category->id
                ]);
            }
        }
    }
}
