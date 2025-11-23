<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table('products')->insert([
//            Appitizers
            [
                'category_id' => 1,
                'name' => 'Critter Crunch',
                'price' => 529,
                'description' => 'Served with garlic mayo'
            ],
            [
                'category_id' => 1,
                'name' => 'Finger Fish',
                'price' => 799,
                'description' => 'Served with french fries & tartar sauce'
            ],
            [
                'category_id' => 1,
                'name' => 'Aleezay Buffalo Wings',
                'price' => 759,
                'description' => null
            ],
            [
                'category_id' => 1,
                'name' => 'Dynamite Wings',
                'price' => 759,
                'description' => null
            ],
            [
                'category_id' => 1,
                'name' => 'Dynamite Shrimps',
                'price' => 799,
                'description' => null
            ],
            [
                'category_id' => 1,
                'name' => 'Finger Chicken',
                'price' => 749,
                'description' => 'Served with fries & garlic mayo sauce'
            ],
            [
                'category_id' => 1,
                'name' => 'Stuffed Chicken Tenderloins Stick',
                'price' => 999,
                'description' => 'Stuffed filet of chicken with jalapeno cheese & herbs with french fries & sauce'
            ],
            [
                'category_id' => 1,
                'name' => 'Sea Basket',
                'price' => 999,
                'description' => 'Crispy fried mixed seafood, french fries & special grilled tangy mushroom'
            ],
            [
                'category_id' => 1,
                'name' => 'Pizza Loaded Fries',
                'price' => 999,
                'description' => null
            ],
            [
                'category_id' => 1,
                'name' => 'Fried Prawns',
                'price' => 949,
                'description' => 'Served with fries & tarar souce'
            ],

            [
                'category_id' => 1,
                'name' => 'Fish & Chips',
                'price' => 949,
                'description' => 'Served with french frise & tarar souce'
            ],
            [
                'category_id' => 1,
                'name' => 'Tem Pura Prawns',
                'price' => 999,
                'description' => null
            ],


            //soups
            [
                'category_id' => 2,
                'name' => 'Thai Green Peas Soup',
                'price' => 459,
                'description' => 'Served with garlic bread & garnished with cilantro oil'
            ],
            [
                'category_id' => 2,
                'name' => 'Our Style Hot & Sour Soup',
                'price' => 459,
                'description' => 'Served with garlic bread & chilli oil'
            ],
            [
                'category_id' => 2,
                'name' => 'Traditional Corn Soup',
                'price' => 459,
                'description' => 'Served with oregano bread'
            ],
            [
                'category_id' => 2,
                'name' => 'Chicken Almond Soup',
                'price' => 499,
                'description' => 'Served with garlic bread'
            ],
            [
                'category_id' => 2,
                'name' => 'Chicken Porcini Mushroom Soup',
                'price' => 459,
                'description' => 'Served with parsley croutons finished with oregano oil'
            ],
            [
                'category_id' => 2,
                'name' => 'Shrimp Cream Soup',
                'price' => 499,
                'description' => 'Served with shrimp & cream garlic bread'
            ],

            //salad
            [
                'category_id' => 4,
                'name' => 'Russian Salad',
                'price' => 999,
                'description' => 'Russian Salad'
            ],

//            Continental
                [
                    'category_id' => 4,
                    'name' => 'Spinach Milano Chicken',
                    'price' => 1649,
                    'description' => 'Served with fried chicken, topping hot sauce, cheddar & mozzarella cheese, spaghetti & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Maxican Chicken Cheese Steak',
                    'price' => 1649,
                    'description' => 'Served with mayo, italian sauce, fried chicken & penne pasta'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Stuffed Scallop Chili Chicken',
                    'price' => 1699,
                    'description' => 'Stuffed chicken cheddar & mozzarella cheese & spinach with creamy sauce with garlic rice'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Mozzarella Chicken',
                    'price' => 1629,
                    'description' => 'Served with chopped mozzarella cheese, oregano white mushroom sauce & vegetable rice'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Pepper Grilled Chicken Steak',
                    'price' => 1629,
                    'description' => 'Served with pepper creamy red sauce & rice sauteed vegetables'
                ],

                [
                    'category_id' => 4,
                    'name' => 'Stuffed Napoleon Chicken',
                    'price' => 1649,
                    'description' => 'Served with stuffed fillet, cheddar mozzarella cheese, jalapeno sauce, rice & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Mushroom Olive Chicken',
                    'price' => 1599,
                    'description' => 'Served with cubed chicken, mushroom, olive, capsicum, fresh cream with rice & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Chicken Parmesan',
                    'price' => 1599,
                    'description' => 'Served with penne pasta, crumbed chicken, marinara & parmesan cheese'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Americano Chicken Steak',
                    'price' => 1599,
                    'description' => 'Served with mushroom red creamy sauce, egg fried rice or mashed potato & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Chicken Jalapeno Cheese Steak',
                    'price' => 1639,
                    'description' => 'Steak with cheddar cheese, jalapeno brown sauce, rice & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Peri Peri Grilled Chicken',
                    'price' => 1679,
                    'description' => 'Half grilled chicken served with spicy peri peri rice & vegetables'
                ],

                [
                    'category_id' => 4,
                    'name' => 'Green Caper Chicken Steak',
                    'price' => 1549,
                    'description' => 'Served with green caper sauce, garlic rice & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Chicken Pepper Steak',
                    'price' => 1559,
                    'description' => 'Served with grilled chicken black pepper sauce'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Crispy Chicken',
                    'price' => 1599,
                    'description' => 'Served with steak fried chicken jalapeno sauce, rice & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'ALA Chefs Steak',
                    'price' => 1549,
                    'description' => 'Served with mushroom jalapeno white creamy sauce, rice & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Western Chicken',
                    'price' => 1579,
                    'description' => 'American style chicken, creamy herb spicy sauce, rice & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Honey Mustard Chicken',
                    'price' => 1599,
                    'description' => 'Served with fried chicken, honey mustard sauce, rice & sauteed vegetables'
                ],

                [
                    'category_id' => 4,
                    'name' => 'Chicken Caprese',
                    'price' => 1549,
                    'description' => 'Stuffed with cheddar cheese, sliced tomatoes, creamy red sauce, rice & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Grilled Chicken Steak',
                    'price' => 1499,
                    'description' => 'Served with peanut onion sauce, rice & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Moroccan Chicken Steak',
                    'price' => 1559,
                    'description' => 'Served with fillet chicken, garlic rice, sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Chicken Allakive',
                    'price' => 1659,
                    'description' => 'Served with jalapeno sauce, vegetable rice & sauteed vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Mushroom Chicken Steak',
                    'price' => 1549,
                    'description' => 'Served with brown mushroom sauce, rice & vegetables'
                ],
                [
                    'category_id' => 4,
                    'name' => 'Pope Eight Chicken',
                    'price' => 1559,
                    'description' => 'Served with white creamy sauce, rice & vegetables'
                ],

//            chinese and thai
                [
                    'category_id' => 5,
                    'name' => 'Chicken Manchurian',
                    'price' => 1249,
                    'description' => 'Served with egg fried rice'
                ],
                [
                    'category_id' => 5,
                    'name' => 'Chicken Chowmien',
                    'price' => 1049,
                    'description' => null
                ],
                [
                    'category_id' => 5,
                    'name' => 'Chicken Chilli Dry',
                    'price' => 1299,
                    'description' => 'Served with garlic rice'
                ],
                [
                    'category_id' => 5,
                    'name' => 'Sweet & Sour Chicken',
                    'price' => 949,
                    'description' => 'Served with vegetable rice'
                ],
                [
                    'category_id' => 5,
                    'name' => 'Thai Chicken With Mushroom',
                    'price' => 1299,
                    'description' => 'Served with garlic rice'
                ],
                [
                    'category_id' => 5,
                    'name' => 'Thai Fish',
                    'price' => 1349,
                    'description' => 'Served with garlic rice'
                ],
                [
                    'category_id' => 5,
                    'name' => 'Dhaka Chicken',
                    'price' => 1299,
                    'description' => 'Served with fries'
                ],
                [
                    'category_id' => 5,
                    'name' => 'Honey Chilli Chicken',
                    'price' => 1299,
                    'description' => 'Served with egg fried rice'
                ],
                [
                    'category_id' => 5,
                    'name' => 'Hot Schezwan Chicken',
                    'price' => 1299,
                    'description' => 'Served with vegetable rice'
                ],

//            beef
                [
                    'name' => 'Mushroom Beef Steak',
                    'price' => 1899,
                    'category_id' => 6,
                    'description' => 'Served with creamy mushroom sauce & mashed potato'
                ],
                [
                    'name' => 'Pepper Beef Steak',
                    'price' => 1899,
                    'category_id' => 6,
                    'description' => 'Served with black pepper corn sauce & mashed potato'
                ],
                [
                    'name' => 'Mexican Beef Steak',
                    'price' => 1899,
                    'category_id' => 6,
                    'description' => 'Served with prime beef tender, Mexican sauce & mashed potato'
                ],
                [
                    'name' => 'Fillet Mignon Beef Steak',
                    'price' => 1899,
                    'category_id' => 6,
                    'description' => 'Served with pepper, corn, mushroom sauce & mashed potato'
                ],
                [
                    'name' => 'Mozzarella Beef Steak',
                    'price' => 1999,
                    'category_id' => 6,
                    'description' => 'Served with mashed potato'
                ],

                //sea food

                [
                    'name' => 'Pepper Fried Fish',
                    'price' => 1799,
                    'category_id' => 7,
                    'description' => 'Served with fried fish, white pepper, creamy sauce, lemon rice and sauteed vegetables'
                ],
                [
                    'name' => 'Crispy Fish',
                    'price' => 1799,
                    'category_id' => 7,
                    'description' => 'Served with crispy fried coated fish, lemon rice, enhanced with creamy herb sauce and vegetable cup'
                ],
                [
                    'name' => 'Stuffed Fish',
                    'price' => 1799,
                    'category_id' => 7,
                    'description' => 'Served with stuffed fish, flavored mix vegetables, enhanced with pepper corn chilli sauce and rice'
                ],
                [
                    'name' => 'Italian Fish',
                    'price' => 1799,
                    'category_id' => 7,
                    'description' => 'Served with grilled fish fillet, white creamy sauce, plain rice and sauteed vegetables'
                ],
                [
                    'name' => 'Sniper X Popper',
                    'price' => 1899,
                    'category_id' => 7,
                    'description' => 'Served with pan fried fish, white creamy sauce, egg fried rice and sauteed vegetables'
                ],
                [
                    'name' => 'White Alaska Fish',
                    'price' => 1849,
                    'category_id' => 7,
                    'description' => 'Grilled fish served with sauce, pickle chilli creamy sauce and vegetable rice'
                ],

//            pasta
                [
                    'name' => 'Oven Baked Pasta',
                    'price' => 1159,
                    'category_id' => 8,
                    'description' => 'Baked pasta dish'
                ],
                [
                    'name' => 'Chicken & Mushroom Spaghetti',
                    'price' => 899,
                    'category_id' => 8,
                    'description' => 'Served with pan seared thyme, chicken prepared with mushroom cream sauce'
                ],
                [
                    'name' => 'Our Tagliatelle',
                    'price' => 999,
                    'category_id' => 8,
                    'description' => 'Served with fettuccine pasta enhanced with spicy herb and roasted chicken'
                ],
                [
                    'name' => 'Silk Chik Pasta',
                    'price' => 999,
                    'category_id' => 8,
                    'description' => 'Served with penne enhanced with spicy herb and roasted chicken'
                ],
                [
                    'name' => 'Carbonara Pasta',
                    'price' => 1050,
                    'category_id' => 8,
                    'description' => 'Served with Mexican vegetables and cheese fettuccine pasta'
                ],
                [
                    'name' => 'Chicken Fettuccine',
                    'price' => 1050,
                    'category_id' => 8,
                    'description' => 'Served with chicken fettuccine with spinach, cream sauce and pepper'
                ],
                [
                    'name' => 'Penne Alla R Zalia',
                    'price' => 1079,
                    'category_id' => 8,
                    'description' => 'Served with enhanced chicken, bell pepper, olive mushrooms in spicy tomato sauce'
                ],
                [
                    'name' => 'Fettuccine Alfredo',
                    'price' => 1089,
                    'category_id' => 8,
                    'description' => 'Creamy Alfredo style fettuccine pasta'
                ],

//            sandwitch

                [
                    'name' => 'BBQ Sandwich',
                    'price' => 759,
                    'category_id' => 9,
                    'description' => 'Served with enhanced fried onions, cheese, BBQ sauce, coleslaw & french fries'
                ],
                [
                    'name' => 'Mexican Chicken Panini',
                    'price' => 759,
                    'category_id' => 9,
                    'description' => 'Served with mixed vegetables, chicken strips, seasonal homemade dressing, french fries & coleslaw'
                ],
                [
                    'name' => 'Grilled Chicken Cheese Sandwich',
                    'price' => 729,
                    'category_id' => 9,
                    'description' => 'Served with grilled chicken, coleslaw & french fries'
                ],
                [
                    'name' => 'Smoked Chicken Panini',
                    'price' => 749,
                    'category_id' => 9,
                    'description' => 'Served with smoked chicken, cheese, tomato, cucumber, lettuce, french fries & coleslaw'
                ],
                [
                    'name' => 'Grilled Chicken & Jalapeno Sandwich',
                    'price' => 749,
                    'category_id' => 9,
                    'description' => 'Served with cheese, jalapeno, cucumber, tomato, iceberg, coleslaw & french fries'
                ],
                [
                    'name' => 'Classic Club Sandwich',
                    'price' => 769,
                    'category_id' => 9,
                    'description' => 'Served with coleslaw & french fries'
                ],
                [
                    'name' => 'Chef Special Club Sandwich',
                    'price' => 779,
                    'category_id' => 9,
                    'description' => 'Served with coleslaw & french fries'
                ],
                [
                    'name' => 'Chipotle Sandwich',
                    'price' => 849,
                    'category_id' => 9,
                    'description' => 'Chipotle style sandwich'
                ],
                [
                    'name' => 'The Cocktail Sandwich',
                    'price' => 899,
                    'category_id' => 9,
                    'description' => 'Served with french fries & coleslaw'
                ],

//                burger
                [
                    'category_id' => 10,
                    'name' => 'Mexican Zee Burger',
                    'description' => 'Served with mexican sauce & fries',
                    'price' => 729
                ],
                [
                    'category_id' => 10,
                    'name' => 'Spicy Fillet Burger',
                    'description' => 'Served with fried chicken, cheese, tomato, cucumber, lettuce, french fries & coleslaw',
                    'price' => 689
                ],
                [
                    'category_id' => 10,
                    'name' => 'BBQ Chicken Burger',
                    'description' => 'Served with mix vegetable chicken strips seasonal homemade dressing french fries & coleslaw',
                    'price' => 679
                ],
                [
                    'category_id' => 10,
                    'name' => 'Chicken Jalapeno Cheese Burger',
                    'description' => 'Served with grilled chicken, coleslaw & french fries',
                    'price' => 679
                ],
                [
                    'category_id' => 10,
                    'name' => 'Chicken Italiano Burger',
                    'description' => 'Served with grilled onions',
                    'price' => 799
                ],
                [
                    'category_id' => 10,
                    'name' => 'Grilled Chicken Cheese Burger',
                    'description' => 'Served with french fries & coleslaw',
                    'price' => 749
                ],
                [
                    'category_id' => 10,
                    'name' => 'Juicy Burger',
                    'description' => 'Served with mexican sauce, french fries & coleslaw',
                    'price' => 799
                ],
                [
                    'category_id' => 10,
                    'name' => 'Permeshan Burger',
                    'description' => 'Served with boneless chicken breast coated with Italian bread crumbs & coleslaw',
                    'price' => 829
                ],
                [
                    'category_id' => 10,
                    'name' => 'Perfect Zinger Burger',
                    'description' => 'Served with fried Thai chicken & sriracha sauce',
                    'price' => 749
                ],
                [
                    'category_id' => 10,
                    'name' => 'Supreme Chicken Burger',
                    'description' => 'Served with fried chicken & top cajun sauce with fries & coleslaw',
                    'price' => 749
                ],
                [
                    'category_id' => 10,
                    'name' => 'Katsu Fried Chicken Burger',
                    'description' => 'Served with fried chicken & pickle vege & sriracha sauce with fries',
                    'price' => 899
                ],

//            pizza
                [
                    'category_id' => 11,
                    'name' => 'Veggie Lover Pizza (Regular)',
                    'description' => 'Bell pepper, tomato sauce, tomato, onion, black olive, mushroom, jalapeno, cheese',
                    'price' => 1049
                ],
                [
                    'category_id' => 11,
                    'name' => 'Veggie Lover Pizza (Large)',
                    'description' => 'Bell pepper, tomato sauce, tomato, onion, black olive, mushroom, jalapeno, cheese',
                    'price' => 1449
                ],

                [
                    'category_id' => 11,
                    'name' => 'Cheese Gold Pizza (Regular)',
                    'description' => 'Mozzarella & cheddar cheese, onion, green chilli',
                    'price' => 1049
                ],
                [
                    'category_id' => 11,
                    'name' => 'Cheese Gold Pizza (Large)',
                    'description' => 'Mozzarella & cheddar cheese, onion, green chilli',
                    'price' => 1449
                ],

                [
                    'category_id' => 11,
                    'name' => 'Chicken Tikka Pizza (Regular)',
                    'description' => 'Chicken tikka, cheese, marinated on tasty tomato sauce',
                    'price' => 1069
                ],
                [
                    'category_id' => 11,
                    'name' => 'Chicken Tikka Pizza (Large)',
                    'description' => 'Chicken tikka, cheese, marinated on tasty tomato sauce',
                    'price' => 1549
                ],

                [
                    'category_id' => 11,
                    'name' => 'Chicken Fajita Pizza (Regular)',
                    'description' => 'Grilled chicken, tomato, jalapeno, onion, bell pepper',
                    'price' => 1069
                ],
                [
                    'category_id' => 11,
                    'name' => 'Chicken Fajita Pizza (Large)',
                    'description' => 'Grilled chicken, tomato, jalapeno, onion, bell pepper',
                    'price' => 1549
                ],

                [
                    'category_id' => 11,
                    'name' => 'Achari Pizza (Regular)',
                    'description' => 'Achari chicken, cheese, tomato sauce, bell pepper, onion, sweet corn',
                    'price' => 1099
                ],
                [
                    'category_id' => 11,
                    'name' => 'Achari Pizza (Large)',
                    'description' => 'Achari chicken, cheese, tomato sauce, bell pepper, onion, sweet corn',
                    'price' => 1599
                ],

                [
                    'category_id' => 11,
                    'name' => 'Confila Pizza (Regular)',
                    'description' => 'Chicken, onion, green chilli, tomato, jalapenos, bell pepper',
                    'price' => 1099
                ],
                [
                    'category_id' => 11,
                    'name' => 'Confila Pizza (Large)',
                    'description' => 'Chicken, onion, green chilli, tomato, jalapenos, bell pepper',
                    'price' => 1599
                ],

                [
                    'category_id' => 11,
                    'name' => 'Masooms Special Pizza (Regular)',
                    'description' => 'Chicken, onion, mushroom, olive, tomato sauce, cheese',
                    'price' => 1099
                ],
                [
                    'category_id' => 11,
                    'name' => 'Masooms Special Pizza (Large)',
                    'description' => 'Chicken, onion, mushroom, olive, tomato sauce, cheese',
                    'price' => 1599
                ],

                [
                    'category_id' => 11,
                    'name' => 'Peri Peri Deep Dish Pizza (Regular)',
                    'description' => 'Peri peri crust pizza',
                    'price' => 1799
                ],
                [
                    'category_id' => 11,
                    'name' => 'Peri Peri Deep Dish Pizza (Large)',
                    'description' => 'Peri peri crust pizza',
                    'price' => 2199
                ],

            //desserts
                [
                    "category_id" => 12,
                    "name"        => "White angel cake slice",
                    'description' => null,
                    "price"       => 450
                ],
                [
                    "category_id" => 12,
                    "name"        => "Brownie with ice cream",
                    'description' => null,
                    "price"       => 550
                ],
                [
                    "category_id" => 12,
                    "name"        => "Molten lava muffin with ice cream",
                    'description' => null,
                    "price"       => 599
                ],
                [
                    "category_id" => 12,
                    "name"        => "Cake Alaska with ice cream",
                    'description' => null,
                    "price"       => 629
                ],

//                 Soft Drinks
                [
                    "category_id" => 13,
                    "name"        => "Mineral Water (Small)",
                    'description' => null,
                    "price"       => 120
                ],
                [
                    "category_id" => 13,
                    "name"        => "Mineral Water (Large)",
                    'description' => null,
                    "price"       => 199
                ],
                [
                    "category_id" => 13,
                    "name"        => "Soft Drink",
                    'description' => null,
                    "price"       => 199
                ],
                [
                    "category_id" => 13,
                    "name"        => "Diet Soft Drink",
                    'description' => null,
                    "price"       => 199
                ],
                [
                    "category_id" => 13,
                    "name"        => "Fresh Lime",
                    'description' => null,
                    "price"       => 199
                ],

//                 Smoothies
                [
                    "category_id" => 14,
                    "name"        => "Banana Smoothie",
                    'description' => null,
                    "price"       => 479
                ],
                [
                    "category_id" => 14,
                    "name"        => "Blueberry Smoothie",
                    'description' => null,
                    "price"       => 479
                ],
                [
                    "category_id" => 14,
                    "name"        => "Strawberry Smoothie",
                    'description' => null,
                    "price"       => 479
                ],
                [
                    "category_id" => 14,
                    "name"        => "Masoom’s Special Smoothie",
                    'description' => null,
                    "price"       => 499
                ],

            [
                "category_id" => 15,
                "name"        => "Groovy Shake",
                'description' => null,
                "price"       => 499
            ],

            [
                "category_id" => 16,
                "name"        => "Mocktail",
                'description' => null,
                "price"       => 499
            ],
            [
                "category_id" => 17,
                "name"        => "Cakes",
                'description' => null,
                "price"       => 499
            ],



        ]);

    }
}
