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
        foreach (Category::all() as $category) {
            Product::factory()
                ->count(rand(10, 20))
                ->create(['category_id' => $category->id]);
        }
        // featured products
        Product::factory()->create(['category_id' => 1, 'name' => 'rtx 3090', 'featured' => 1]);
        Product::factory()->create(['category_id' => 2, 'name' => 'rysen 5950x', 'featured' => 1]);
        Product::factory()->create(['category_id' => 3, 'name' => 'ram ddr5', 'featured' => 1]);
        Product::factory()->create(['category_id' => 1, 'name' => 'rx 6800XT', 'featured' => 1]);
        Product::factory()->create(['category_id' => 2, 'name' => 'intel i9 12900K', 'featured' => 1]);
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