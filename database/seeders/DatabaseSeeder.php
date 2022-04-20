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
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin')
        ]);
        User::factory()->create([
            'name' => 'normal',
            'email' => 'normal@gmail.com',
            'password' => bcrypt('normal')
        ]);
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

        //CPUs
        {
            Product::factory()->create([
                'name' => 'amd rysen 3 3200g',
                'category_id' => 1,
                'price' => 10000,
                'images' => json_encode([
                    'main' => 'rysen-3-32000g_gpz00q.jpg',
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
                'price' => 11000,
                'images' => json_encode([
                    'main' => 'i3-10100_zezrgg.jpg',
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
                    'main' => 'rysen-3-3300x_po0r0n.jpg',
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
                    'main' => 'rysen-5-3600_xshyni.jpg',
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
                'price' => 20000,
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
        // GPUs
        {
            Product::factory()->create([
                'name' => 'nvidia gtx 1650 super',
                'category_id' => 2,
                'price' => 18000,
                'images' => json_encode([
                    'main' => 'gtx-1650-super.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'nvidia gtx 1660 super',
                'category_id' => 2,
                'price' => 23000,
                'images' => json_encode([
                    'main' => 'gtx-1660-super.jpeg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'nvidia rtx 2060 super',
                'category_id' => 2,
                'price' => 28000,
                'images' => json_encode([
                    'main' => 'rtx-2060-super.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'Nvidia rtx 3060 ti',
                'category_id' => 2,
                'price' => 40000,
                'images' => json_encode([
                    'main' => 'rtx-3060-ti.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'nvidia rtx 3080',
                'category_id' => 2,
                'price' => 80000,
                'featured' => 1,
                'images' => json_encode([
                    'main' => 'rtx-3080-ti.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'amd rx 6700 xt',
                'category_id' => 2,
                'price' => 60000,
                'images' => json_encode([
                    'main' => 'rx-6700-xt.png',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'amd rx 6800 xt',
                'category_id' => 2,
                'price' => 75000,
                'featured' => 1,
                'images' => json_encode([
                    'main' => 'rx-6800-xt.webp',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'nvidia rtx 3090',
                'category_id' => 2,
                'price' => 150000,
                'featured' => 1,
                'images' => json_encode([
                    'main' => 'rtx-3090.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
        }
        // RAMs 
        {
            Product::factory()->create([
                'name' => 'adata ram ddr4 3600mhz 16gb 2x8gb',
                'category_id' => 3,
                'price' => 6000,
                'images' => json_encode([
                    'main' => 'adata-ram.webp',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'curssair ram ddr5 5100mhz 16gb 2x8gb',
                'category_id' => 3,
                'price' => 12000,
                'featured' => 1,
                'images' => json_encode([
                    'main' => 'curssaire-ram.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'kingston ram ddr4 3200mhz 8gb 1x8gb',
                'category_id' => 3,
                'price' => 3500,
                'images' => json_encode([
                    'main' => 'kingstron-ram.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
        }
        // storage
        {
            Product::factory()->create([
                'name' => 'adata ssd 500gb',
                'category_id' => 4,
                'price' => 4000,
                'images' => json_encode([
                    'main' => 'adata-ssd.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'seagate HDD 2Tb(hard disk, hard drive)',
                'category_id' => 4,
                'price' => 8000,
                'images' => json_encode([
                    'main' => 'seagate-hdd.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
        }
        // mohterboards
        {
            Product::factory()->create([
                'name' => 'asus prime b450-MA',
                'category_id' => 7,
                'price' => 4000,
                'images' => json_encode([
                    'main' => 'asus-b4580-ma.jpg',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
            Product::factory()->create([
                'name' => 'msi mag b550 tomahawk',
                'category_id' => 7,
                'price' => 4000,
                'images' => json_encode([
                    'main' => 'msi-b550.png',
                    'others' => [
                        'image1.jpg',
                        'image2.jpg',
                        'image3.jpg'
                    ]
                ])
            ]);
        }

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