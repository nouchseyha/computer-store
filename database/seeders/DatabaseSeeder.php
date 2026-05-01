<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name'     => 'Admin TK',
            'email'    => 'admin@computertkstore.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        // Demo Customer
        User::create([
            'name'     => 'John Doe',
            'email'    => 'customer@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Categories
        $cats = [
            ['name' => 'Laptops',     'slug' => 'laptops',     'description' => 'Portable computers for work and play'],
            ['name' => 'Desktops',    'slug' => 'desktops',    'description' => 'Powerful desktop computers'],
            ['name' => 'Monitors',    'slug' => 'monitors',    'description' => 'High-quality displays'],
            ['name' => 'Keyboards',   'slug' => 'keyboards',   'description' => 'Mechanical and membrane keyboards'],
            ['name' => 'Mice',        'slug' => 'mice',        'description' => 'Gaming and office mice'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Computer accessories and peripherals'],
            ['name' => 'Components',  'slug' => 'components',  'description' => 'CPU, RAM, GPU and more'],
            ['name' => 'Storage',     'slug' => 'storage',     'description' => 'SSDs, HDDs, and flash drives'],
        ];
        foreach ($cats as $c) {
            Category::create(array_merge($c, ['is_active' => true]));
        }

        $cid = fn($slug) => Category::where('slug', $slug)->first()->id;

        $products = [
            // Laptops
            ['category_id' => $cid('laptops'), 'name' => 'Dell XPS 15 9530', 'brand' => 'Dell', 'price' => 1799.99, 'sale_price' => 1599.99, 'stock' => 15, 'is_featured' => true, 'short_description' => '15.6" OLED, Intel Core i7-13700H, 16GB RAM, 512GB SSD', 'description' => 'The Dell XPS 15 features a stunning OLED display, powerful Intel Core i7 processor, and premium build quality.', 'image' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=400&h=300&fit=crop'],
            ['category_id' => $cid('laptops'), 'name' => 'MacBook Pro 14 inch', 'brand' => 'Apple', 'price' => 1999.00, 'stock' => 10, 'is_featured' => true, 'short_description' => 'Apple M3 Pro chip, 18GB RAM, 512GB SSD, Liquid Retina XDR', 'description' => 'The MacBook Pro 14-inch with M3 Pro chip delivers exceptional performance.', 'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=300&fit=crop'],
            ['category_id' => $cid('laptops'), 'name' => 'ASUS ROG Zephyrus G14', 'brand' => 'ASUS', 'price' => 1499.99, 'sale_price' => 1299.99, 'stock' => 8, 'is_featured' => true, 'short_description' => 'AMD Ryzen 9, RTX 4060, 16GB RAM, 1TB SSD', 'description' => 'A powerful gaming laptop with AMD Ryzen 9 processor and NVIDIA RTX 4060 graphics.', 'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=400&h=300&fit=crop'],
            ['category_id' => $cid('laptops'), 'name' => 'Lenovo ThinkPad X1 Carbon', 'brand' => 'Lenovo', 'price' => 1349.00, 'stock' => 12, 'short_description' => 'Intel Core i7, 16GB RAM, 512GB SSD, 14" IPS', 'description' => 'The ThinkPad X1 Carbon is the ultimate business laptop with legendary durability.', 'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400&h=300&fit=crop'],
            ['category_id' => $cid('laptops'), 'name' => 'HP Spectre x360 14', 'brand' => 'HP', 'price' => 1249.99, 'sale_price' => 1099.99, 'stock' => 6, 'short_description' => 'Intel Core i7, 16GB RAM, 512GB SSD, OLED Touch', 'description' => 'The HP Spectre x360 is a premium 2-in-1 laptop with stunning OLED display.', 'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=300&fit=crop'],
            // Desktops
            ['category_id' => $cid('desktops'), 'name' => 'Custom Gaming PC RTX 4080', 'brand' => 'TK Build', 'price' => 2499.99, 'stock' => 5, 'is_featured' => true, 'short_description' => 'Intel Core i9-13900K, RTX 4080, 32GB DDR5, 2TB NVMe', 'description' => 'High-performance gaming desktop with the latest Intel Core i9 and NVIDIA RTX 4080.', 'image' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=400&h=300&fit=crop'],
            ['category_id' => $cid('desktops'), 'name' => 'iMac 24 inch M3', 'brand' => 'Apple', 'price' => 1699.00, 'stock' => 4, 'short_description' => 'Apple M3, 8GB RAM, 256GB SSD, 24" Retina 4.5K', 'description' => 'The iMac with M3 chip is thin, light, and incredibly powerful.', 'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=400&h=300&fit=crop'],
            ['category_id' => $cid('desktops'), 'name' => 'Dell OptiPlex 7010', 'brand' => 'Dell', 'price' => 849.99, 'sale_price' => 749.99, 'stock' => 20, 'short_description' => 'Intel Core i5-13500, 16GB RAM, 512GB SSD', 'description' => 'A reliable business desktop for office productivity.', 'image' => 'https://images.unsplash.com/photo-1547082299-de196ea013d6?w=400&h=300&fit=crop'],
            // Monitors
            ['category_id' => $cid('monitors'), 'name' => 'LG 27 inch 4K UHD Monitor', 'brand' => 'LG', 'price' => 449.99, 'sale_price' => 379.99, 'stock' => 25, 'is_featured' => true, 'short_description' => '27" IPS, 4K UHD, 60Hz, HDR400, USB-C', 'description' => 'Stunning 4K clarity with wide color gamut and HDR support.', 'image' => 'https://images.unsplash.com/photo-1527443195645-1133f7f28990?w=400&h=300&fit=crop'],
            ['category_id' => $cid('monitors'), 'name' => 'Samsung 32 inch Curved Gaming', 'brand' => 'Samsung', 'price' => 399.99, 'stock' => 18, 'short_description' => '32" VA Curved, 1440p, 165Hz, 1ms', 'description' => 'Immersive curved gaming monitor with fast refresh rate.', 'image' => 'https://images.unsplash.com/photo-1585792180666-f7347c490ee2?w=400&h=300&fit=crop'],
            // Keyboards
            ['category_id' => $cid('keyboards'), 'name' => 'Keychron K2 Mechanical Keyboard', 'brand' => 'Keychron', 'price' => 89.99, 'stock' => 50, 'is_featured' => true, 'short_description' => 'TKL, Wireless, RGB, Brown Switches', 'description' => 'Compact wireless mechanical keyboard with RGB backlight.', 'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400&h=300&fit=crop'],
            ['category_id' => $cid('keyboards'), 'name' => 'Logitech MX Keys S', 'brand' => 'Logitech', 'price' => 119.99, 'sale_price' => 99.99, 'stock' => 35, 'short_description' => 'Full-size, Wireless, Backlit, Multi-device', 'description' => 'Premium wireless keyboard for productivity.', 'image' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=400&h=300&fit=crop'],
            // Mice
            ['category_id' => $cid('mice'), 'name' => 'Logitech MX Master 3S', 'brand' => 'Logitech', 'price' => 99.99, 'sale_price' => 79.99, 'stock' => 40, 'is_featured' => true, 'short_description' => 'Wireless, 8000 DPI, Ergonomic, Multi-device', 'description' => 'The ultimate productivity mouse with MagSpeed scroll wheel.', 'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=300&fit=crop'],
            ['category_id' => $cid('mice'), 'name' => 'Razer DeathAdder V3', 'brand' => 'Razer', 'price' => 69.99, 'stock' => 30, 'short_description' => 'Wired, 30000 DPI, Ergonomic', 'description' => 'High-performance gaming mouse with Focus Pro 30K sensor.', 'image' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=400&h=300&fit=crop'],
            // Accessories
            ['category_id' => $cid('accessories'), 'name' => 'USB-C Hub 10-in-1', 'brand' => 'Anker', 'price' => 49.99, 'sale_price' => 39.99, 'stock' => 60, 'short_description' => 'HDMI 4K, 3x USB-A, SD Card, 100W PD', 'description' => 'Expand your laptop connectivity with this versatile USB-C hub.', 'image' => 'https://images.unsplash.com/photo-1625842268584-8f3296236761?w=400&h=300&fit=crop'],
            ['category_id' => $cid('accessories'), 'name' => 'Laptop Stand Aluminum', 'brand' => 'Nexstand', 'price' => 34.99, 'stock' => 45, 'short_description' => 'Adjustable height, Aluminum, Foldable', 'description' => 'Ergonomic aluminum laptop stand for better posture.', 'image' => 'https://images.unsplash.com/photo-1593642634367-d91a135587b5?w=400&h=300&fit=crop'],
            ['category_id' => $cid('accessories'), 'name' => 'Webcam 4K Ultra HD', 'brand' => 'Logitech', 'price' => 199.99, 'sale_price' => 159.99, 'stock' => 20, 'short_description' => '4K, 30fps, Auto-focus, Built-in mic', 'description' => 'Professional 4K webcam for video conferencing and streaming.', 'image' => 'https://images.unsplash.com/photo-1587826080692-f439cd0b70da?w=400&h=300&fit=crop'],
            // Components
            ['category_id' => $cid('components'), 'name' => 'NVIDIA RTX 4070 Ti GPU', 'brand' => 'NVIDIA', 'price' => 799.99, 'stock' => 8, 'is_featured' => true, 'short_description' => '12GB GDDR6X, DLSS 3, Ray Tracing', 'description' => 'High-performance GPU for 4K gaming and creative work.', 'image' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=400&h=300&fit=crop'],
            ['category_id' => $cid('components'), 'name' => 'AMD Ryzen 9 7950X CPU', 'brand' => 'AMD', 'price' => 549.99, 'sale_price' => 499.99, 'stock' => 12, 'short_description' => '16 cores, 32 threads, 5.7GHz boost', 'description' => 'Flagship AMD desktop processor for extreme performance.', 'image' => 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=400&h=300&fit=crop'],
            ['category_id' => $cid('components'), 'name' => 'Corsair 32GB DDR5 6000MHz', 'brand' => 'Corsair', 'price' => 129.99, 'stock' => 30, 'short_description' => '32GB (2x16GB), DDR5-6000, CL36, RGB', 'description' => 'High-speed DDR5 memory kit for next-gen platforms.', 'image' => 'https://images.unsplash.com/photo-1562976540-1502c2145186?w=400&h=300&fit=crop'],
            // Storage
            ['category_id' => $cid('storage'), 'name' => 'Samsung 990 Pro 2TB NVMe SSD', 'brand' => 'Samsung', 'price' => 179.99, 'sale_price' => 149.99, 'stock' => 40, 'is_featured' => true, 'short_description' => '2TB, PCIe 4.0, 7450MB/s read, M.2 2280', 'description' => 'Ultra-fast NVMe SSD for gaming and professional use.', 'image' => 'https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=400&h=300&fit=crop'],
            ['category_id' => $cid('storage'), 'name' => 'WD Black 4TB HDD', 'brand' => 'Western Digital', 'price' => 89.99, 'stock' => 25, 'short_description' => '4TB, 7200 RPM, SATA 6Gb/s, 256MB Cache', 'description' => 'High-capacity hard drive for mass storage needs.', 'image' => 'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?w=400&h=300&fit=crop'],
            ['category_id' => $cid('storage'), 'name' => 'SanDisk 1TB Portable SSD', 'brand' => 'SanDisk', 'price' => 99.99, 'sale_price' => 79.99, 'stock' => 35, 'short_description' => '1TB, USB 3.2, 1050MB/s, Compact', 'description' => 'Fast and compact portable SSD for on-the-go storage.', 'image' => 'https://images.unsplash.com/photo-1606229365485-93a3b8ee0385?w=400&h=300&fit=crop'],
        ];

        foreach ($products as $p) {
            $sku = strtoupper(substr(preg_replace('/[^a-z0-9]/i', '', $p['name']), 0, 6)) . '-' . rand(1000, 9999);
            Product::create(array_merge($p, [
                'slug'       => Str::slug($p['name']),
                'sku'        => $sku,
                'is_active'  => true,
                'is_featured' => $p['is_featured'] ?? false,
            ]));
        }
    }
}
