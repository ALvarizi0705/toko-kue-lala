<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DirectSale;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data.
     */
    public function run(): void
    {
        // ---------------------------------------------------------------------
        // 1. SETTINGS
        // ---------------------------------------------------------------------
        $settings = [
            'shop_name' => 'Toko Kue Lala',
            'address' => 'Jl. Matraman Dalam III No. 26B, Jakarta Timur',
            'phone' => '081234567890',
            'pickup_hours' => 'Senin - Sabtu, 08.00 - 20.00 WIB',
            'bank_bca' => '1234567890 (a/n Lala Komalasari)',
            'dana' => '081234567890 (Toko Kue Lala)',
            'gopay' => '081234567890 (Toko Kue Lala)',
            'min_order_days' => 1,
            'qris_image' => null,
        ];
        foreach ($settings as $key => $val) {
            Setting::set($key, $val);
        }

        // ---------------------------------------------------------------------
        // 2. USERS
        // ---------------------------------------------------------------------
        $pemilik = User::create([
            'name' => 'Lala Komalasari',
            'phone' => '081234567890',
            'email' => 'pemilik@tokokuelalala.id',
            'password' => Hash::make('password'),
            'role' => 'pemilik',
            'is_active' => true,
        ]);

        $kasir = User::create([
            'name' => 'Budi Santoso (Kasir)',
            'phone' => '081234567891',
            'email' => 'kasir@tokokuelalala.id',
            'password' => Hash::make('password'),
            'role' => 'staf_kasir',
            'is_active' => true,
        ]);

        $produksi = User::create([
            'name' => 'Dewi Lestari (Dapur)',
            'phone' => '081234567892',
            'email' => 'produksi@tokokuelalala.id',
            'password' => Hash::make('password'),
            'role' => 'staf_produksi',
            'is_active' => true,
        ]);

        $pelanggan1 = User::create([
            'name' => 'Rina Wijaya',
            'phone' => '081298765432',
            'email' => 'pelanggan1@email.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'is_active' => true,
        ]);

        $pelanggan2 = User::create([
            'name' => 'Andi Pratama',
            'phone' => '081287654321',
            'email' => 'pelanggan2@email.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'is_active' => true,
        ]);

        // ---------------------------------------------------------------------
        // 3. CATEGORIES (3 Kategori Sesuai Menu Asli)
        // ---------------------------------------------------------------------
        $catBolu        = Category::create(['name' => 'Bolu & Kue Panggang', 'is_active' => true]);
        $catTradisional = Category::create(['name' => 'Kue Tradisional', 'is_active' => true]);
        $catPastry      = Category::create(['name' => 'Snack & Pastry', 'is_active' => true]);

        // ---------------------------------------------------------------------
        // 4. REAL PRODUCTS (Sesuai Catatan Penjualan Toko Kue Lala)
        // ---------------------------------------------------------------------
        $productsData = [
            [
                'category_id' => $catBolu->id,
                'name' => 'Kue Bolu Marmer',
                'description' => 'Bolu marmer klasik dengan corak cokelat khas yang lembut, wangi butter harum, dan tekstur empuk moist di setiap gigitan.',
                'price' => 85000,
                'is_active' => true,
            ],
            [
                'category_id' => $catTradisional->id,
                'name' => 'Kue Apem',
                'description' => 'Kue apem kukus tradisional khas Toko Kue Lala dengan rasa manis legit gurih alami, tekstur kenyal empuk yang nikmat.',
                'price' => 45000,
                'is_active' => true,
            ],
            [
                'category_id' => $catBolu->id,
                'name' => 'Kue Bolu Tape',
                'description' => 'Bolu tape singkong legit dengan perpaduan tape manis matang sempurna, beraroma harum menggoda dengan taburan keju gurih.',
                'price' => 90000,
                'is_active' => true,
            ],
            [
                'category_id' => $catBolu->id,
                'name' => 'Kue Bolu Caramel / Sarang Semut (Ukuran S)',
                'description' => 'Kue bolu karamel legit dengan rongga sarang semut sempurna, rasa manis karamel legit yang khas dan kenyal moist. Ukuran Small.',
                'price' => 100000,
                'is_active' => true,
            ],
            [
                'category_id' => $catBolu->id,
                'name' => 'Kue Bolu Caramel / Sarang Semut (Ukuran L)',
                'description' => 'Kue bolu karamel legit dengan rongga sarang semut sempurna, rasa manis karamel legit yang khas dan kenyal moist. Ukuran Large loyang besar.',
                'price' => 130000,
                'is_active' => true,
            ],
            [
                'category_id' => $catPastry->id,
                'name' => 'Kue Soes (Isi 6 Buah)',
                'description' => 'Sus vla lembut renyah di luar dengan isian vla vanila susu yang melimpah, lumer, dan tidak terlalu manis. 1 porsi isi 6 buah.',
                'price' => 20000,
                'is_active' => true,
            ],
            [
                'category_id' => $catTradisional->id,
                'name' => 'Kue Lumpur (Isi 6 Buah)',
                'description' => 'Kue lumpur kentang lembut beraroma pandan dan santan gurih dengan topping kismis manis. 1 porsi isi 6 buah.',
                'price' => 20000,
                'is_active' => true,
            ],
            [
                'category_id' => $catPastry->id,
                'name' => 'Kue Pie Buah (Isi 6 Buah)',
                'description' => 'Mini fruit pie dengan kulit pie yang renyah gurih, vla lembut, serta topping potongan buah segar berkilau. 1 porsi isi 6 buah.',
                'price' => 20000,
                'is_active' => true,
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $pData) {
            $createdProducts[] = Product::create($pData);
        }

        // ---------------------------------------------------------------------
        // 5. ORDERS (Pesanan Masuk dengan Info Pelanggan Lengkap)
        // ---------------------------------------------------------------------
        $customerList = [
            ['name' => 'Rina Wijaya', 'phone' => '081298765432', 'address' => 'Jl. Matraman Raya No. 45, Jakarta Timur', 'user' => $pelanggan1],
            ['name' => 'Andi Pratama', 'phone' => '081287654321', 'address' => 'Jl. Kayu Manis VIII No. 10, Matraman', 'user' => $pelanggan2],
            ['name' => 'Siti Nurhaliza', 'phone' => '085712345678', 'address' => 'Jl. Pisangan Baru Timur No. 22, Jaktim', 'user' => null],
            ['name' => 'Hendra Setiawan', 'phone' => '081389012345', 'address' => 'Ambil Sendiri di Toko', 'user' => null],
            ['name' => 'Maya Kartika', 'phone' => '081912349988', 'address' => 'Jl. Utan Kayu Utara No. 5B, Jakarta Timur', 'user' => null],
        ];

        $statuses = [
            'menunggu_pembayaran',
            'diproses',
            'siap_diambil',
            'selesai',
        ];

        for ($i = 1; $i <= 12; $i++) {
            $cust = $customerList[($i - 1) % count($customerList)];
            $status = $statuses[($i - 1) % count($statuses)];
            $orderDate = Carbon::now()->subDays(12 - $i)->subHours(rand(1, 10));
            $pickupDate = Carbon::now()->addDays(($i % 4))->toDateString();

            $orderNumber = 'LALA-' . $orderDate->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $cust['user']?->id,
                'customer_name' => $cust['name'],
                'customer_phone' => $cust['phone'],
                'customer_address' => $cust['address'],
                'pickup_date' => $pickupDate,
                'note' => ($i % 3 === 0) ? 'Tolong siapkan pisau kue dan lilin.' : null,
                'total' => 0,
                'status' => $status,
                'payment_method' => 'whatsapp',
                'verified_by' => in_array($status, ['diproses', 'siap_diambil', 'selesai']) ? $kasir->id : null,
                'verified_at' => in_array($status, ['diproses', 'siap_diambil', 'selesai']) ? $orderDate->copy()->addMinutes(20) : null,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            $p1 = $createdProducts[($i - 1) % count($createdProducts)];
            $qty1 = rand(1, 2);
            $sub1 = $p1->price * $qty1;

            $order->orderItems()->create([
                'product_id' => $p1->id,
                'product_name' => $p1->name,
                'unit_price' => $p1->price,
                'qty' => $qty1,
                'size' => null,
                'cake_text' => ($i % 2 === 0) ? 'Selamat Ulang Tahun ' . $cust['name'] : null,
                'custom_note' => null,
                'subtotal' => $sub1,
            ]);

            $total = $sub1;

            if ($i % 3 === 0) {
                $p2 = $createdProducts[($i + 2) % count($createdProducts)];
                $qty2 = 1;
                $sub2 = $p2->price * $qty2;

                $order->orderItems()->create([
                    'product_id' => $p2->id,
                    'product_name' => $p2->name,
                    'unit_price' => $p2->price,
                    'qty' => $qty2,
                    'size' => null,
                    'cake_text' => null,
                    'custom_note' => null,
                    'subtotal' => $sub2,
                ]);
                $total += $sub2;
            }

            $order->update(['total' => $total]);

            // Log status
            $order->orderStatusLogs()->create([
                'from_status' => null,
                'to_status' => 'menunggu_pembayaran',
                'user_id' => $cust['user']?->id,
                'note' => 'Pesanan dibuat oleh ' . $cust['name'],
                'created_at' => $orderDate,
            ]);

            if (in_array($status, ['diproses', 'siap_diambil', 'selesai'])) {
                $order->orderStatusLogs()->create([
                    'from_status' => 'menunggu_pembayaran',
                    'to_status' => 'diproses',
                    'user_id' => $kasir->id,
                    'note' => 'Pembayaran dikonfirmasi via WhatsApp oleh toko. Sedang diproses dapur.',
                    'created_at' => $orderDate->copy()->addMinutes(20),
                ]);
            }

            if (in_array($status, ['siap_diambil', 'selesai'])) {
                $order->orderStatusLogs()->create([
                    'from_status' => 'diproses',
                    'to_status' => 'siap_diambil',
                    'user_id' => $produksi->id,
                    'note' => 'Kue selesai dibuat & siap diambil di toko.',
                    'created_at' => $orderDate->copy()->addHours(2),
                ]);
            }

            if ($status === 'selesai') {
                $order->orderStatusLogs()->create([
                    'from_status' => 'siap_diambil',
                    'to_status' => 'selesai',
                    'user_id' => $kasir->id,
                    'note' => 'Pesanan telah diambil / dikirim kepada pelanggan.',
                    'created_at' => $orderDate->copy()->addHours(5),
                ]);
            }
        }

        // ---------------------------------------------------------------------
        // 6. DIRECT SALES (Penjualan Langsung di Kasir)
        // ---------------------------------------------------------------------
        for ($k = 1; $k <= 5; $k++) {
            $saleDate = Carbon::now()->subDays(5 - $k)->subHours(rand(1, 8));
            $saleNumber = 'POS-' . $saleDate->format('Ymd') . '-' . str_pad($k, 4, '0', STR_PAD_LEFT);

            $directSale = DirectSale::create([
                'sale_number' => $saleNumber,
                'user_id' => $kasir->id,
                'payment_method' => ['tunai', 'qris', 'transfer'][$k % 3],
                'total' => 0,
                'sold_at' => $saleDate,
                'created_at' => $saleDate,
                'updated_at' => $saleDate,
            ]);

            $itemProd = $createdProducts[$k % count($createdProducts)];
            $sub = $itemProd->price * 1;

            $directSale->items()->create([
                'product_id' => $itemProd->id,
                'product_name' => $itemProd->name,
                'unit_price' => $itemProd->price,
                'qty' => 1,
                'subtotal' => $sub,
            ]);

            $directSale->update(['total' => $sub]);
        }
    }
}
