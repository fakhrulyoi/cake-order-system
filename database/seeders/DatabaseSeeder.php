<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StoreSetting;
use App\Models\Cake;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@cakeshop.com',
            'password' => bcrypt('password123')
        ]);

        // Create store settings
        StoreSetting::create([
            'store_name' => 'Kacang Pool Pak Majid',
            'whatsapp_number' => '60111991 6985',
            'theme_color' => '#14532D',
            'description' => 'Rasa asli dalam setiap suapan. Pesan terus dari menu digital kami dengan mudah dan pantas.',
            'store_status' => 'open'
        ]);

        // Sample cakes based on your images
        $cakes = [
            [
                'name' => 'Italian Cake',
                'description' => 'boleh pilih jenis kek, size 12inci',
                'price' => 350.00,
                'size' => '12 inch',
                'category' => 'Birthday Cake',
                'status' => 'available'
            ],
            [
                'name' => '2tier Wedding Cake',
                'description' => 'starting rm350 size 6\'&8\'',
                'price' => 350.00,
                'size' => '6" & 8"',
                'category' => 'Wedding Cake',
                'status' => 'available'
            ],
            [
                'name' => 'Kekbatik Ganache',
                'description' => 'size 7inci',
                'price' => 45.00,
                'size' => '7 inch',
                'category' => 'Kek Batik',
                'status' => 'available'
            ],
            [
                'name' => 'Tornado Original',
                'description' => 'available semasa open order only',
                'price' => 21.00,
                'size' => '8 inch',
                'category' => 'Tornado',
                'status' => 'available'
            ],
            [
                'name' => 'Red Velvet',
                'description' => 'size 8inci',
                'price' => 80.00,
                'size' => '8 inch',
                'category' => 'Red Velvet',
                'status' => 'available'
            ],
            [
                'name' => 'Victoria Sandwich Cake',
                'description' => 'buttercake, smbc & fresh milk cream',
                'price' => 100.00,
                'size' => '8 inch',
                'category' => 'Buttercake',
                'status' => 'available'
            ],
            [
                'name' => 'Pandan Gula Melaka',
                'description' => 'kekbutter, smbc & gula melaka',
                'price' => 90.00,
                'size' => '8 inch',
                'category' => 'Pandan',
                'status' => 'available'
            ],
            [
                'name' => 'Brownies Tower',
                'description' => '',
                'price' => 95.00,
                'size' => 'Tower',
                'category' => 'Brownies',
                'status' => 'available'
            ],
            [
                'name' => 'Matilda Choc Cake',
                'description' => '',
                'price' => 150.00,
                'size' => '8 inch',
                'category' => 'Chocolate',
                'status' => 'available'
            ],
            [
                'name' => 'Kek Batik Matcha',
                'description' => 'size 7inci',
                'price' => 60.00,
                'size' => '7 inch',
                'category' => 'Kek Batik',
                'status' => 'available'
            ],
            [
                'name' => 'Brownies Cheesecake',
                'description' => 'size 7inci',
                'price' => 70.00,
                'size' => '7 inch',
                'category' => 'Brownies',
                'status' => 'available'
            ],
            [
                'name' => 'Congo Bars',
                'description' => '100% pure butter & choc couvature',
                'price' => 50.00,
                'size' => '7 inch',
                'category' => 'Bars',
                'status' => 'available'
            ],
            [
                'name' => 'Burnt Cheese Cake',
                'description' => 'size 6inci',
                'price' => 45.00,
                'size' => '6 inch',
                'category' => 'Cheesecake',
                'status' => 'available'
            ],
            [
                'name' => 'Doorgift Buttercake',
                'description' => 'tempahan sekurangnya sebulan sebelum tarikh acara',
                'price' => 3.80,
                'size' => 'Mini',
                'category' => 'Doorgift',
                'status' => 'available'
            ],
        ];

        foreach ($cakes as $cake) {
            Cake::create($cake);
        }
    }
}
