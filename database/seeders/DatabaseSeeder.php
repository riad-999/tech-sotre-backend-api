<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // creating users and their addresses
        User::factory()
            ->count(200)
            ->create();
        $users = User::all();
        foreach ($users as $user) {
            UserAddress::factory()
                ->create(['user_id' => $user->id]);
        }
        // creating categories and their products
        $categories_names = [
            'cpu',
            'graphics card',
            'ram',
            'disks',
            'cpu cooler',
            'psu',
            'motherboard',
            'case',
            'peripheral'
        ];
        foreach ($categories_names as $category_name) {
            Category::factory()
                ->create(['name' => $category_name]);
        }
        // foreach (Category::all() as $category) {
        //     Product::factory()
        //         ->count(rand(10, 20))
        //         ->create(['category_id' => $category->id]);
        // }

        {
            Product::factory()->create([
                'name' => 'amd rysen 3 3200g',
                'category_id' => 1,
                'price' => 10000,
                'images' => json_encode([
                    'main' => 'rysen-3-32000g.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'intel i3 10100',
                'category_id' => 1,
                'price' => 110,
                'images' => json_encode([
                    'main' => 'i3-10100.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'amd rysen 3 3300x',
                'category_id' => 1,
                'price' => 12000,
                'images' => json_encode([
                    'main' => 'rysen-3-3300x.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'amd rysen 5 3600',
                'category_id' => 1,
                'price' => 16000,
                'images' => json_encode([
                    'main' => 'rysen-5-3600.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'amd rysen 5 5600x',
                'category_id' => 1,
                'price' => 30000,
                'images' => json_encode([
                    'main' => 'rysen-5-5600x.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'amd rysen 7 3700x',
                'category_id' => 1,
                'price' => 38000,
                'images' => json_encode([
                    'main' => 'rysen-7-3700x.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'amd rysen 9 5950x',
                'category_id' => 1,
                'price' => 90000,
                'images' => json_encode([
                    'main' => 'rysen-9-5950x.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'intel i5 12600k',
                'category_id' => 1,
                'price' => 25000,
                'images' => json_encode([
                    'main' => 'i5-12600k.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'intel i5 12600f',
                'category_id' => 1,
                'price' => 2000,
                'images' => json_encode([
                    'main' => 'i5-12600f.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'intel i7 12700k',
                'category_id' => 1,
                'price' => 32000,
                'images' => json_encode([
                    'main' => 'i7-12700k.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'intel i9 12900k',
                'category_id' => 1,
                'price' => 80000,
                'featured' => 1,
                'images' => json_encode([
                    'main' => 'i9-12900k.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
        }

        // featured products
        Product::factory()->create(['category_id' => 1, 'name' => 'rtx 3090', 'featured' => 1]);
        Product::factory()->create(['category_id' => 2, 'name' => 'rysen 5950x', 'featured' => 1]);
        Product::factory()->create(['category_id' => 3, 'name' => 'ram ddr5', 'featured' => 1]);
        Product::factory()->create(['category_id' => 1, 'name' => 'rx 6800XT', 'featured' => 1]);
        // creating orders and thier addresses and link them to products
        foreach ($users as $user) {
            $order = Order::factory()
                ->count(rand(0, 2))
                ->hasAttached(Product::all()->random(rand(1, 10)), ['quantity' => rand(1, 3)])
                ->create(['buyer_id' => $user->id]);
        }
        $orders = Order::all();
        foreach ($orders as $order) {
            OrderAddress::factory()
                ->create(['order_id' => $order->id]);
        }
        // creating reviews
        $orders = Order::all();
        foreach ($orders as $order) {
            $user = $order->buyer;
            $products = $order->products;
            foreach ($products as $product) {
                Review::factory()
                    ->create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                    ]);
            }
        }
    }
}