<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExtraProductsSeeder extends Seeder
{
    public function run(): void
    {
        $cid = fn($slug) => Category::where('slug', $slug)->value('id');

        $products = [
            // Laptops
            [
                'category_id'       => $cid('laptops'),
                'name'              => 'Acer Nitro 5 Gaming Laptop',
                'brand'             => 'Acer',
                'price'             => 899.99,
                'sale_price'        => 799.99,
                'stock'             => 14,
                'is_featured'       => false,
                'short_description' => 'AMD Ryzen 5 7535HS, RTX 4050, 16GB RAM, 512GB SSD, 15.6" 144Hz',
                'description'       => 'The Acer Nitro 5 delivers solid gaming performance at an affordable price with AMD Ryzen 5 and NVIDIA RTX 4050.',
                'image'             => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=400&h=300&fit=crop',
            ],
            [
                'category_id'       => $cid('laptops'),
                'name'              => 'MSI Stealth 16 Studio',
                'brand'             => 'MSI',
                'price'             => 2199.00,
                'sale_price'        => null,
                'stock'             => 5,
                'is_featured'       => true,
                'short_description' => 'Intel Core i9-13900H, RTX 4070, 32GB DDR5, 1TB NVMe, 16" QHD+',
                'description'       => 'MSI Stealth 16 Studio is a powerhouse for creators and gamers with top-tier specs in a slim chassis.',
                'image'             => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=300&fit=crop',
            ],

            // Monitors
            [
                'category_id'       => $cid('monitors'),
                'name'              => 'ASUS ProArt PA279CRV 27"',
                'brand'             => 'ASUS',
                'price'             => 699.99,
                'sale_price'        => 599.99,
                'stock'             => 10,
                'is_featured'       => false,
                'short_description' => '27" IPS, 4K UHD, 99% Adobe RGB, USB-C 96W, Color Calibrated',
                'description'       => 'Professional color-accurate display for designers and photographers.',
                'image'             => 'https://images.unsplash.com/photo-1527443195645-1133f7f28990?w=400&h=300&fit=crop',
            ],

            // Keyboards
            [
                'category_id'       => $cid('keyboards'),
                'name'              => 'Corsair K70 RGB Pro',
                'brand'             => 'Corsair',
                'price'             => 139.99,
                'sale_price'        => 109.99,
                'stock'             => 28,
                'is_featured'       => false,
                'short_description' => 'Full-size, Cherry MX Red, Per-key RGB, Aluminum Frame',
                'description'       => 'The Corsair K70 RGB Pro is a premium mechanical keyboard with Cherry MX switches and vibrant RGB lighting.',
                'image'             => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400&h=300&fit=crop',
            ],
            [
                'category_id'       => $cid('keyboards'),
                'name'              => 'Razer BlackWidow V4 Pro',
                'brand'             => 'Razer',
                'price'             => 229.99,
                'sale_price'        => null,
                'stock'             => 15,
                'is_featured'       => false,
                'short_description' => 'Full-size, Razer Yellow Switches, Wireless, Chroma RGB',
                'description'       => 'The Razer BlackWidow V4 Pro offers wireless freedom with Razer\'s fastest linear switches.',
                'image'             => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=400&h=300&fit=crop',
            ],

            // Mice
            [
                'category_id'       => $cid('mice'),
                'name'              => 'SteelSeries Aerox 5 Wireless',
                'brand'             => 'SteelSeries',
                'price'             => 129.99,
                'sale_price'        => 99.99,
                'stock'             => 22,
                'is_featured'       => false,
                'short_description' => 'Wireless, 18000 DPI, Ultra-light 74g, 9 Buttons',
                'description'       => 'Ultra-lightweight wireless gaming mouse with TrueMove Air sensor.',
                'image'             => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=300&fit=crop',
            ],

            // Accessories
            [
                'category_id'       => $cid('accessories'),
                'name'              => 'Logitech C920 HD Pro Webcam',
                'brand'             => 'Logitech',
                'price'             => 79.99,
                'sale_price'        => 64.99,
                'stock'             => 33,
                'is_featured'       => false,
                'short_description' => '1080p/30fps, Autofocus, Dual Stereo Mic, USB',
                'description'       => 'The Logitech C920 delivers crisp 1080p video for video calls and streaming.',
                'image'             => 'https://images.unsplash.com/photo-1587826080692-f439cd0b70da?w=400&h=300&fit=crop',
            ],
            [
                'category_id'       => $cid('accessories'),
                'name'              => 'Razer Kraken V3 Headset',
                'brand'             => 'Razer',
                'price'             => 99.99,
                'sale_price'        => 79.99,
                'stock'             => 18,
                'is_featured'       => false,
                'short_description' => 'USB, 7.1 Surround Sound, THX Spatial Audio, RGB',
                'description'       => 'Immersive gaming audio with THX Spatial Audio and comfortable memory foam ear cushions.',
                'image'             => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop',
            ],

            // Components
            [
                'category_id'       => $cid('components'),
                'name'              => 'ASUS ROG STRIX B650E-F Motherboard',
                'brand'             => 'ASUS',
                'price'             => 299.99,
                'sale_price'        => null,
                'stock'             => 9,
                'is_featured'       => false,
                'short_description' => 'AM5, DDR5, PCIe 5.0, WiFi 6E, 2.5G LAN, ATX',
                'description'       => 'High-end AM5 motherboard for AMD Ryzen 7000 series with PCIe 5.0 and DDR5 support.',
                'image'             => 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=400&h=300&fit=crop',
            ],

            // Storage
            [
                'category_id'       => $cid('storage'),
                'name'              => 'Seagate Expansion 2TB Portable HDD',
                'brand'             => 'Seagate',
                'price'             => 64.99,
                'sale_price'        => 54.99,
                'stock'             => 50,
                'is_featured'       => false,
                'short_description' => '2TB, USB 3.0, Plug & Play, Compact Design',
                'description'       => 'Reliable portable storage for backups and file transfers with USB 3.0 speed.',
                'image'             => 'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?w=400&h=300&fit=crop',
            ],
        ];

        foreach ($products as $p) {
            if (!$p['category_id']) continue; // skip if category not found

            $sku = strtoupper(substr(preg_replace('/[^a-z0-9]/i', '', $p['name']), 0, 6))
                 . '-' . rand(1000, 9999);

            Product::create(array_merge($p, [
                'slug'       => Str::slug($p['name']),
                'sku'        => $sku,
                'is_active'  => true,
                'is_featured' => $p['is_featured'],
            ]));
        }

        echo "10 products added successfully.\n";
    }
}
