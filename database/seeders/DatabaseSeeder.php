<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::create([
            'name' => 'Admin TenCoffe',
            'email' => 'admin@tencoffe.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 2. Categories
        $categories = [
            ['name' => 'Basic Coffee', 'description' => 'Kopi dasar pilihan', 'sort_order' => 1],
            ['name' => 'Coffee Flavour', 'description' => 'Kopi dengan aneka rasa', 'sort_order' => 2],
            ['name' => 'Signature', 'description' => 'Racikan spesial TenCoffe', 'sort_order' => 3],
            ['name' => 'Non Coffee', 'description' => 'Minuman non-kopi', 'sort_order' => 4],
            ['name' => 'Beverages', 'description' => 'Aneka minuman segar', 'sort_order' => 5],
            ['name' => 'Maincourse', 'description' => 'Hidangan utama pilihan', 'sort_order' => 6],
            ['name' => 'Lite Bites', 'description' => 'Camilan & snack ringan', 'sort_order' => 7],
            ['name' => 'Others', 'description' => 'Dessert & lainnya', 'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'sort_order' => $cat['sort_order'],
                'is_active' => true,
            ]);
        }

        $basicCoffee = Category::where('slug', 'basic-coffee')->first();
        $coffeeFlavour = Category::where('slug', 'coffee-flavour')->first();
        $signature = Category::where('slug', 'signature')->first();
        $nonCoffee = Category::where('slug', 'non-coffee')->first();
        $beverages = Category::where('slug', 'beverages')->first();
        $maincourse = Category::where('slug', 'maincourse')->first();
        $liteBites = Category::where('slug', 'lite-bites')->first();
        $others = Category::where('slug', 'others')->first();

        // ============================================================
        // 3. Products — sesuai menu asli TenCoffe
        // ============================================================

        // --- BASIC COFFEE (H/C variants) ---
        $basicCoffeeProducts = [
            ['name' => 'Espresso', 'price' => 23000, 'description' => 'Espresso shot murni', 'has_variants' => false],
            ['name' => 'Manual Brew', 'price_hot' => 27000, 'price_cold' => 28000, 'description' => 'Kopi seduh manual pilihan'],
            ['name' => 'Americano', 'price_hot' => 26000, 'price_cold' => 28000, 'description' => 'Espresso dengan air panas'],
            ['name' => 'Coffee Latte', 'price_hot' => 26000, 'price_cold' => 28000, 'description' => 'Espresso dengan steamed milk'],
            ['name' => 'Cappuccino', 'price_hot' => 26000, 'price_cold' => 28000, 'description' => 'Espresso dengan susu foam lembut'],
        ];

        foreach ($basicCoffeeProducts as $i => $p) {
            $hasVariants = $p['has_variants'] ?? true;
            Product::create([
                'category_id' => $basicCoffee->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'price' => $hasVariants ? $p['price_hot'] : $p['price'],
                'price_hot' => $hasVariants ? $p['price_hot'] : null,
                'price_cold' => $hasVariants ? $p['price_cold'] : null,
                'has_variants' => $hasVariants,
                'is_active' => true,
                'is_featured' => in_array($i, [2, 4]), // Americano & Cappuccino best seller
            ]);
        }

        // --- COFFEE FLAVOUR (H/C variants) ---
        $coffeeFlavourProducts = [
            ['name' => 'Caramel Latte', 'price_hot' => 26000, 'price_cold' => 28000, 'description' => 'Latte dengan sirup caramel'],
            ['name' => 'Vanilla Latte', 'price_hot' => 26000, 'price_cold' => 28000, 'description' => 'Latte dengan sirup vanilla'],
            ['name' => 'Tiramisu Latte', 'price_hot' => 26000, 'price_cold' => 28000, 'description' => 'Latte dengan rasa tiramisu'],
            ['name' => 'Hazelnut Latte', 'price_hot' => 26000, 'price_cold' => 28000, 'description' => 'Latte dengan sirup hazelnut'],
        ];

        foreach ($coffeeFlavourProducts as $i => $p) {
            Product::create([
                'category_id' => $coffeeFlavour->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'price' => $p['price_hot'],
                'price_hot' => $p['price_hot'],
                'price_cold' => $p['price_cold'],
                'has_variants' => true,
                'is_active' => true,
                'is_featured' => $i == 0, // Caramel Latte best seller
            ]);
        }

        // --- SIGNATURE (mostly cold-only, kecuali Sanger Coffee) ---
        $signatureProducts = [
            ['name' => 'Ten Coffee', 'price' => 22000, 'description' => 'Racikan khas TenCoffe, wajib coba!'],
            ['name' => 'Frapucino Choco', 'price' => 32000, 'description' => 'Frapucino coklat premium blend'],
            ['name' => 'Choco Beng', 'price' => 27000, 'description' => 'Coklat kopi blend spesial'],
            ['name' => 'Salted Popcorn', 'price' => 25000, 'description' => 'Kopi dengan rasa salted popcorn unik'],
            ['name' => 'Shaken', 'price' => 24000, 'description' => 'Kopi kocok segar'],
            ['name' => 'Osvana Coffee', 'price' => 23000, 'description' => 'Kopi racikan Osvana style'],
            ['name' => 'Midnight Corn', 'price' => 23000, 'description' => 'Kopi dengan sentuhan jagung creamy'],
            ['name' => 'Colada Coffee', 'price' => 23000, 'description' => 'Kopi tropical colada blend'],
            ['name' => 'Sanger Coffee', 'price_hot' => 16000, 'price_cold' => 18000, 'description' => 'Kopi sanger khas Aceh', 'has_variants' => true],
            ['name' => 'Es Kopi Balok', 'price' => 24000, 'description' => 'Es kopi susu dalam bentuk balok unik'],
        ];

        foreach ($signatureProducts as $i => $p) {
            $hasVariants = $p['has_variants'] ?? false;
            Product::create([
                'category_id' => $signature->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'price' => $hasVariants ? $p['price_hot'] : $p['price'],
                'price_hot' => $hasVariants ? $p['price_hot'] : null,
                'price_cold' => $hasVariants ? $p['price_cold'] : null,
                'has_variants' => $hasVariants,
                'is_active' => true,
                'is_featured' => in_array($i, [0, 1, 9]), // Ten Coffee, Frapucino Choco, Es Kopi Balok best seller
            ]);
        }

        // --- NON COFFEE ---
        $nonCoffeeProducts = [
            ['name' => 'Chocolate', 'price_hot' => 22000, 'price_cold' => 23000, 'description' => 'Coklat premium hangat atau dingin', 'has_variants' => true],
            ['name' => 'Matcha Strawberry', 'price' => 35000, 'description' => 'Matcha dengan perpaduan strawberry segar'],
            ['name' => 'Dirty Matcha', 'price' => 34000, 'description' => 'Matcha latte creamy kekinian'],
        ];

        foreach ($nonCoffeeProducts as $i => $p) {
            $hasVariants = $p['has_variants'] ?? false;
            Product::create([
                'category_id' => $nonCoffee->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'price' => $hasVariants ? $p['price_hot'] : $p['price'],
                'price_hot' => $hasVariants ? ($p['price_hot'] ?? null) : null,
                'price_cold' => $hasVariants ? ($p['price_cold'] ?? null) : null,
                'has_variants' => $hasVariants,
                'is_active' => true,
                'is_featured' => in_array($i, [0, 2]), // Chocolate & Dirty Matcha best seller
            ]);
        }

        // --- BEVERAGES ---
        $beveragesProducts = [
            ['name' => 'Red Oasis', 'price' => 32000, 'description' => 'Minuman segar red oasis'],
            ['name' => 'Passion Tea', 'price' => 32000, 'description' => 'Teh passion fruit segar'],
            ['name' => 'Lychee Tea', 'price' => 27000, 'description' => 'Teh dengan leci segar'],
            ['name' => 'Happy Kiwi', 'price' => 27000, 'description' => 'Minuman kiwi segar menyehatkan'],
            ['name' => 'Ice Tea', 'price' => 17000, 'description' => 'Es teh segar'],
            ['name' => 'Mineral Water', 'price' => 8000, 'description' => 'Air mineral'],
        ];

        foreach ($beveragesProducts as $i => $p) {
            Product::create([
                'category_id' => $beverages->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'price' => $p['price'],
                'has_variants' => false,
                'is_active' => true,
                'is_featured' => in_array($i, [1]), // Passion Tea best seller
            ]);
        }

        // --- MAINCOURSE ---
        $maincourseProducts = [
            ['name' => 'Soup Iga w/ Steam Rice', 'price' => 54000, 'description' => 'Sup iga sapi empuk dengan nasi putih hangat'],
            ['name' => 'Chicken Brest Mashroom', 'price' => 49000, 'description' => 'Dada ayam panggang dengan saus jamur'],
            ['name' => 'Sauce Ribb', 'price' => 46000, 'description' => 'Iga bakar dengan saus spesial'],
            ['name' => 'Nasi Goreng Special', 'price' => 49000, 'description' => 'Nasi goreng spesial khas TenCoffe'],
            ['name' => 'Nasi Barendo Rica', 'price' => 39000, 'description' => 'Nasi dengan ayam barendo rica-rica pedas'],
            ['name' => 'Chicken Crispy Smash', 'price' => 33000, 'description' => 'Ayam crispy smash juicy'],
            ['name' => 'Mie Aceh Daging (Tumis/Goreng)', 'price' => 34000, 'description' => 'Mie Aceh daging sapi tumis atau goreng'],
            ['name' => 'Mie Aceh Ayam (Tumis/Goreng)', 'price' => 30000, 'description' => 'Mie Aceh ayam tumis atau goreng'],
            ['name' => 'Mie Tiaw Seafood', 'price' => 48000, 'description' => 'Mie tiaw dengan aneka seafood segar'],
            ['name' => 'Spaghetti Carbonara', 'price' => 37000, 'description' => 'Pasta carbonara creamy klasik'],
            ['name' => 'Rice Bowl Dori Sambal Matah', 'price' => 32000, 'description' => 'Rice bowl ikan dori dengan sambal matah segar'],
            ['name' => 'Rice Bowl Beef Blackpaper', 'price' => 32000, 'description' => 'Rice bowl daging sapi saus blackpepper'],
            ['name' => 'Rice Bowl Teriyaki', 'price' => 28000, 'description' => 'Rice bowl ayam teriyaki'],
        ];

        foreach ($maincourseProducts as $i => $p) {
            Product::create([
                'category_id' => $maincourse->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'price' => $p['price'],
                'has_variants' => false,
                'is_active' => true,
                'is_featured' => in_array($i, [0, 3, 5]), // Soup Iga, Nasi Goreng Special, Chicken Crispy Smash
            ]);
        }

        // --- LITE BITES ---
        $liteBitesProducts = [
            ['name' => 'Mix Platter', 'price' => 51000, 'description' => 'Platter campuran aneka snack favorit'],
            ['name' => 'Shrimp Mayonase', 'price' => 42500, 'description' => 'Udang goreng dengan saus mayonaise'],
            ['name' => 'Crispy Enoki Treats', 'price' => 27000, 'description' => 'Jamur enoki goreng crispy'],
            ['name' => 'French Fries', 'price' => 23000, 'description' => 'Kentang goreng crispy'],
            ['name' => 'Lumpia', 'price' => 20000, 'description' => 'Lumpia goreng isi sayuran'],
            ['name' => 'Pisang Kipas', 'price' => 20000, 'description' => 'Pisang goreng kipas crispy'],
            ['name' => 'Ubi Goreng', 'price' => 17000, 'description' => 'Ubi goreng manis renyah'],
            ['name' => 'Tempe Mendoan', 'price' => 16000, 'description' => 'Tempe mendoan khas Purwokerto'],
        ];

        foreach ($liteBitesProducts as $i => $p) {
            Product::create([
                'category_id' => $liteBites->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'price' => $p['price'],
                'has_variants' => false,
                'is_active' => true,
                'is_featured' => in_array($i, [0, 1]), // Mix Platter, Shrimp Mayonase
            ]);
        }

        // --- OTHERS (Dessert & lainnya) ---
        $othersProducts = [
            ['name' => 'Dark Choco', 'price' => 36000, 'description' => 'Dark chocolate cake premium'],
            ['name' => 'Cheese Cake', 'price' => 36000, 'description' => 'Cheese cake lembut'],
            ['name' => 'Butter Croissant', 'price' => 28000, 'description' => 'Croissant butter renyah'],
            ['name' => 'Doughnuts', 'price' => 12000, 'description' => 'Donat lembut fresh baked'],
        ];

        foreach ($othersProducts as $i => $p) {
            Product::create([
                'category_id' => $others->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['description'],
                'price' => $p['price'],
                'has_variants' => false,
                'is_active' => true,
                'is_featured' => in_array($i, [1]), // Cheese Cake
            ]);
        }

        // 4. Settings
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'TenCoffe', 'type' => 'text', 'group' => 'general'],
            ['key' => 'tagline', 'value' => 'Your Daily Coffee Partner', 'type' => 'text', 'group' => 'general'],
            ['key' => 'email', 'value' => 'tencoffeofficial@gmail.com', 'type' => 'text', 'group' => 'general'],
            ['key' => 'phone', 'value' => '0813-7163-5845', 'type' => 'text', 'group' => 'general'],
            ['key' => 'phone2', 'value' => '0811-7085-555', 'type' => 'text', 'group' => 'general'],
            ['key' => 'address', 'value' => 'Jl. Kopi Susu No. 10, Jakarta Selatan', 'type' => 'text', 'group' => 'general'],
            ['key' => 'operating_hours', 'value' => 'Senin - Minggu, 08:00 - 22:00', 'type' => 'text', 'group' => 'general'],

            // Social
            ['key' => 'instagram', 'value' => 'https://instagram.com/tencoffe', 'type' => 'text', 'group' => 'social'],
            ['key' => 'tiktok', 'value' => 'https://tiktok.com/@tencoffe', 'type' => 'text', 'group' => 'social'],
            ['key' => 'whatsapp', 'value' => '6281371635845', 'type' => 'text', 'group' => 'social'],

            // Order
            ['key' => 'store_whatsapp', 'value' => '6281371635845', 'type' => 'text', 'group' => 'order'],
            ['key' => 'min_order', 'value' => '0', 'type' => 'number', 'group' => 'order'],
            ['key' => 'delivery_fee', 'value' => '10000', 'type' => 'number', 'group' => 'order'],

            // Payment
            ['key' => 'duitku_mode', 'value' => 'sandbox', 'type' => 'text', 'group' => 'payment'],
            ['key' => 'duitku_merchant_code', 'value' => '', 'type' => 'text', 'group' => 'payment'],
            ['key' => 'duitku_api_key', 'value' => '', 'type' => 'text', 'group' => 'payment'],
            ['key' => 'duitku_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'payment'],

            // Special Event
            ['key' => 'special_event_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'special_event'],
            ['key' => 'special_event_emoji', 'value' => '🌙', 'type' => 'text', 'group' => 'special_event'],
            ['key' => 'special_event_badge', 'value' => 'Ramadhan Kareem', 'type' => 'text', 'group' => 'special_event'],
            ['key' => 'special_event_title', 'value' => 'Spesial Menu Ramadhan', 'type' => 'text', 'group' => 'special_event'],
            ['key' => 'special_event_subtitle', 'value' => 'Tersedia selama bulan Ramadhan 2026. Sudah termasuk minuman dan takjil!', 'type' => 'text', 'group' => 'special_event'],
        ];

        foreach ($settings as $s) {
            Setting::create($s);
        }

        // 5. Gallery — Spesial Menu Ramadhan 2026
        $eventImages = [
            [
                'title' => 'Dori Sambal Dabu Dabu',
                'description' => 'Nasi Putih, Dori Goreng Tepung, Sambal Dabu Dabu, Urap, Daun Selada Segar, Tomat Timun Segar. (Es Kasturi + Takjil)',
                'image' => 'images/special-event/ramadan-1.png',
                'price' => 50000,
            ],
            [
                'title' => 'Nasi Ayam Cabai Hijau',
                'description' => 'Nasi Putih, Ayam Goreng, Sambal Cabai Hijau, Urap, Terung Goreng, Daun Selada Segar, Tomat Timun Segar. (Es Kasturi + Takjil)',
                'image' => 'images/special-event/ramadan-2.png',
                'price' => 50000,
            ],
            [
                'title' => 'Nasi Ayam Kremes',
                'description' => 'Nasi Putih, Ayam Goreng Kremes, Sambal Cabai Merah, Urap, Terung Goreng, Daun Selada Segar, Tomat Timun Segar. (Lemon Tea + Takjil)',
                'image' => 'images/special-event/ramadan-3.png',
                'price' => 50000,
            ],
            [
                'title' => 'Tongseng Daging',
                'description' => 'Nasi Putih, Tongseng Daging, Kerupuk Emping, Daun Selada Segar, Tomat Timun Segar. (Lemon Tea + Takjil)',
                'image' => 'images/special-event/ramadan-4.png',
                'price' => 60000,
            ],
        ];

        foreach ($eventImages as $img) {
            Gallery::create([
                'title' => $img['title'],
                'description' => $img['description'],
                'image' => $img['image'],
                'group' => 'special_event',
                'price' => $img['price'],
                'is_active' => true,
            ]);
        }
    }
}
