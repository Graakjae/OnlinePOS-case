<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Margherita',
                'description' => 'Tomatsauce, mozzarella, frisk basilikum',
                'price' => 89.99,
                'tax' => 25,
                'tagName' => '',
                'tagColor' => '',
                'order' => 0
            ],
            [
                'name' => 'Pepperoni',
                'description' => 'Tomatsauce, mozzarella, pepperoni, løg',
                'price' => 99.99,
                'tax' => 25,
                'tagName' => 'Populær 🔥',
                'tagColor' => 'primary',
                'order' => 1
            ],
            [
                'name' => 'Hawaii',
                'description' => 'Tomatsauce, mozzarella, skinke, ananas',
                'price' => 109.99,
                'tax' => 25,
                'order' => 2
            ],
            [
                'name' => 'Vegetar',
                'description' => 'Tomatsauce, mozzarella, champignon, paprika, løg, oliven',
                'price' => 104.99,
                'tax' => 25,
                'tagName' => 'Vegatar 🥗',
                'tagColor' => 'info',
                'order' => 3
            ],
            [
                'name' => 'Cola',
                'description' => '33 cl',
                'price' => 25.00,
                'tax' => 25,
                'tagName' => 'Drikkevarer',
                'tagColor' => 'secondary',
                'order' => 4
            ],
            [
                'name' => 'Øl',
                'description' => '50 cl',
                'price' => 45.00,
                'tax' => 25,
                'tagName' => 'Drikkevarer',
                'tagColor' => 'secondary',
                'order' => 5
            ],
            [
                'name' => 'Vand',
                'description' => '33 cl',
                'price' => 20.00,
                'tax' => 25,
                'tagName' => 'Drikkevarer',
                'tagColor' => 'secondary',
                'order' => 6
            ],
            [
                'name' => 'Quattro Formaggi',
                'description' => 'Tomatsauce, mozzarella, gorgonzola, parmesan, pecorino',
                'price' => 119.99,
                'tax' => 25,
                'tagName' => 'Ny',
                'tagColor' => 'success',
                'order' => 7
            ],
            [
                'name' => 'Calzone',
                'description' => 'Tomatsauce, mozzarella, skinke, champignon, løg',
                'price' => 114.99,
                'tax' => 25,
                'order' => 8
            ],
            [
                'name' => 'Diavola',
                'description' => 'Tomatsauce, mozzarella, salami, chili, løg',
                'price' => 109.99,
                'tax' => 25,
                'tagName' => 'Stærk 🌶️',
                'tagColor' => 'danger',
                'order' => 9
            ],
            [
                'name' => 'BBQ Chicken',
                'description' => 'BBQ sauce, mozzarella, kylling, løg, bacon',
                'price' => 119.99,
                'tax' => 25,
                'tagName' => 'Ny',
                'tagColor' => 'success',
                'order' => 10
            ],
            [
                'name' => 'Prosciutto',
                'description' => 'Tomatsauce, mozzarella, parmesan, prosciutto, rucola',
                'price' => 129.99,
                'tax' => 25,
                'tagName' => 'Premium',
                'tagColor' => 'primary',
                'order' => 11
            ],
            [
                'name' => 'Tonic Water',
                'description' => '33 cl',
                'price' => 25.00,
                'tax' => 25,
                'tagName' => 'Drikkevarer',
                'tagColor' => 'secondary',
                'order' => 12
            ],
            [
                'name' => 'Rødvin',
                'description' => '75 cl',
                'price' => 89.00,
                'tax' => 25,
                'tagName' => 'Drikkevarer',
                'tagColor' => 'secondary',
                'order' => 13
            ],
            [
                'name' => 'Hvidvin',
                'description' => '75 cl',
                'price' => 89.00,
                'tax' => 25,
                'tagName' => 'Drikkevarer',
                'tagColor' => 'secondary',
                'order' => 14
            ],
            [
                'name' => 'Buffalo',
                'description' => 'Buffalo sauce, mozzarella, kylling, løg, dressing',
                'price' => 114.99,
                'tax' => 25,
                'tagName' => 'Stærk 🌶️',
                'tagColor' => 'danger',
                'order' => 15
            ],
            [
                'name' => 'Parma',
                'description' => 'Tomatsauce, mozzarella, parmesan, parmaskinke, rucola',
                'price' => 124.99,
                'tax' => 25,
                'tagName' => 'Premium',
                'tagColor' => 'primary',
                'order' => 16
            ],
            [
                'name' => 'Vegansk',
                'description' => 'Tomatsauce, vegansk ost, champignon, paprika, løg, oliven, rucola',
                'price' => 109.99,
                'tax' => 25,
                'tagName' => 'Vegansk 🥗',
                'tagColor' => 'info',
                'order' => 17
            ],
            [
                'name' => 'Sodavand',
                'description' => '33 cl',
                'price' => 25.00,
                'tax' => 25,
                'tagName' => 'Drikkevarer',
                'tagColor' => 'secondary',
                'order' => 18
            ],
            [
                'name' => 'Cider',
                'description' => '33 cl',
                'price' => 45.00,
                'tax' => 25,
                'tagName' => 'Drikkevarer',
                'tagColor' => 'secondary',
                'order' => 19
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
