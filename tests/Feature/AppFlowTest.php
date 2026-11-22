<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }
    public function test_public_pages_load_successfully(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/katalog')->assertStatus(200);
        $this->get('/keranjang')->assertStatus(200);
        $this->get('/lacak-pesanan')->assertStatus(200);

        $product = Product::first();
        $this->get('/produk/' . $product->id)->assertStatus(200);
    }

    public function test_guest_checkout_and_tracking_flow(): void
    {
        $product = Product::first();

        // 1. Tambah ke keranjang
        $cart = [
            'item_' . $product->id => [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 2,
                'photo' => $product->photo_url,
                'subtotal' => $product->price * 2,
            ],
        ];

        // 2. Akses halaman checkout tanpa login
        $response = $this->withSession(['cart' => $cart])->get('/checkout');
        $response->assertStatus(200);
        $response->assertSee('Data Pemesan');

        // 3. Submit checkout sebagai tamu
        $orderData = [
            'customer_name' => 'Budi Santoso Tamu',
            'customer_phone' => '081299887766',
            'customer_address' => 'Jl. Pisangan Baru No. 10, Jakarta Timur',
            'pickup_date' => now()->addDays(2)->format('Y-m-d'),
            'note' => 'Catatan pesanan tamu',
            'cake_text_' . $product->id => 'Selamat Ulang Tahun',
        ];

        $checkoutResponse = $this->withSession(['cart' => $cart])->post('/checkout', $orderData);
        $order = Order::where('customer_phone', '081299887766')->first();

        $this->assertNotNull($order);
        $this->assertEquals('Budi Santoso Tamu', $order->customer_name);
        $this->assertEquals('whatsapp', $order->payment_method);
        $this->assertNull($order->user_id); // Tamu tanpa login

        $checkoutResponse->assertRedirect(route('order.show', $order->order_number));

        // 4. Lacak pesanan publik
        $trackResponse = $this->get('/pesanan/' . $order->order_number);
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee($order->order_number);
        $trackResponse->assertSee('Budi Santoso Tamu');

        // 5. Cetak nota publik
        $notaResponse = $this->get('/pesanan/' . $order->order_number . '/nota');
        $notaResponse->assertStatus(200);
        $notaResponse->assertSee($order->order_number);
    }

    public function test_admin_dashboard_and_order_management(): void
    {
        $pemilik = User::where('role', 'pemilik')->first();
        $this->actingAs($pemilik);

        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        // Pastikan tidak ada stok bahan lagi di dashboard
        $response->assertDontSee('Peringatan Stok Menipis');

        // Akses daftar pesanan
        $ordersResponse = $this->get('/admin/pesanan');
        $ordersResponse->assertStatus(200);

        // Akses detail pesanan & update status ke diproses
        $order = Order::where('status', 'menunggu_pembayaran')->first();
        if ($order) {
            $showResponse = $this->get('/admin/pesanan/' . $order->id);
            $showResponse->assertStatus(200);

            $updateResponse = $this->patch('/admin/pesanan/' . $order->id . '/status', [
                'status' => 'diproses',
            ]);
            $updateResponse->assertSessionHas('success');
            $this->assertEquals('diproses', $order->fresh()->status);
        }
    }
}
