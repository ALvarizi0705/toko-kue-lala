<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Tampilkan formulir checkout (terbuka untuk umum / tamu tanpa login).
     */
    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('warning', 'Keranjangmu masih kosong. Silakan pilih kue terlebih dahulu.');
        }

        $total = collect($cart)->sum('subtotal');
        $minDays = (int) Setting::get('min_order_days', 1);

        return view('storefront.checkout', compact('cart', 'total', 'minDays'));
    }

    /**
     * Simpan pesanan pelanggan (Nama, No WA, Alamat, Tanggal Ambil).
     */
    public function store(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjangmu masih kosong.');
        }

        $minDays = (int) Setting::get('min_order_days', 1);
        $minDate = now()->addDays($minDays)->toDateString();

        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'pickup_date' => 'required|date|after_or_equal:' . $minDate,
            'note' => 'nullable|string|max:500',
        ], [
            'customer_name.required' => 'Nama lengkap pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi agar toko dapat menghubungi.',
            'pickup_date.required' => 'Tanggal pengambilan di toko wajib dipilih.',
            'pickup_date.after_or_equal' => 'Tanggal ambil minimal H+' . $minDays . ' dari hari ini.',
        ]);

        $order = null;

        DB::transaction(function () use ($request, $cart, &$order) {
            $total = 0;
            $orderNumber = $this->generateOrderNumber();

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => auth()->check() ? auth()->id() : null,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address ?: 'Ambil Sendiri di Toko',
                'pickup_date' => $request->pickup_date,
                'note' => $request->note,
                'total' => 0,
                'status' => 'menunggu_pembayaran',
                'payment_method' => 'whatsapp',
            ]);

            foreach ($cart as $key => $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;

                $qty = max(1, (int) $item['qty']);
                $unitPrice = (float) $product->price;
                $subtotal = $unitPrice * $qty;
                $total += $subtotal;

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $unitPrice,
                    'qty' => $qty,
                    'size' => null, // Pilihan ukuran dihilangkan sesuai permintaan klien
                    'cake_text' => $request->input('cake_text_' . $product->id),
                    'custom_note' => $request->input('custom_note_' . $product->id),
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total' => $total]);

            $order->orderStatusLogs()->create([
                'from_status' => null,
                'to_status' => 'menunggu_pembayaran',
                'user_id' => auth()->check() ? auth()->id() : null,
                'note' => 'Pesanan baru dibuat oleh ' . $request->customer_name . ' (WhatsApp: ' . $request->customer_phone . ').',
            ]);
        });

        session()->forget('cart');

        return redirect()->route('order.show', $order->order_number)
            ->with('success', 'Pesanan #' . $order->order_number . ' berhasil dibuat! Penjual akan menghubungi via WhatsApp, atau kamu bisa langsung chat penjual sekarang.');
    }

    /**
     * Halaman detail status & pelacakan pesanan (Publik dengan Order Number).
     */
    public function trackDetail(Order $order)
    {
        $order->load(['orderItems.product', 'orderStatusLogs']);
        $shopPhone = Setting::get('phone', '081234567890');
        $cleanShopPhone = preg_replace('/[^0-9]/', '', $shopPhone);
        if (str_starts_with($cleanShopPhone, '0')) {
            $cleanShopPhone = '62' . substr($cleanShopPhone, 1);
        }

        $settings = [
            'bank_bca' => Setting::get('bank_bca', '1234567890 (a/n Lala Komalasari)'),
            'dana' => Setting::get('dana', '08123456789 (Toko Kue Lala)'),
            'gopay' => Setting::get('gopay', '08123456789 (Toko Kue Lala)'),
            'qris_image' => Setting::get('qris_image'),
        ];

        return view('storefront.order-detail', compact('order', 'shopPhone', 'cleanShopPhone', 'settings'));
    }

    /**
     * Upload bukti transfer/pembayaran oleh pelanggan (Publik via nomor pesanan).
     */
    public function uploadPayment(Request $request, Order $order)
    {
        if (!in_array($order->status, [Order::STATUS_MENUNGGU_PEMBAYARAN, Order::STATUS_MENUNGGU_VERIFIKASI])) {
            return back()->with('error', 'Status pesanan saat ini tidak memerlukan unggah bukti pembayaran.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'payment_proof.required' => 'File gambar bukti pembayaran wajib diunggah.',
            'payment_proof.image' => 'File bukti harus berupa gambar.',
            'payment_proof.mimes' => 'Format file yang diterima: JPG, JPEG, PNG, WEBP.',
            'payment_proof.max' => 'Ukuran maksimal file gambar adalah 2 MB.',
        ]);

        $file = $request->file('payment_proof');
        $fileName = 'payment_' . Str::random(24) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payments', $fileName, 'public');

        $oldStatus = $order->status;
        $order->update([
            'payment_proof' => $path,
            'status' => Order::STATUS_MENUNGGU_VERIFIKASI,
            'payment_reject_reason' => null,
        ]);

        $order->orderStatusLogs()->create([
            'from_status' => $oldStatus,
            'to_status' => Order::STATUS_MENUNGGU_VERIFIKASI,
            'user_id' => auth()->check() ? auth()->id() : null,
            'note' => 'Bukti pembayaran diunggah oleh ' . ($order->customer_name ?? 'pelanggan') . '.',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim! Kasir toko kami akan segera memverifikasi pesanan Anda.');
    }

    /**
     * Halaman formulir lacak pesanan.
     */
    public function trackForm(Request $request)
    {
        $query = $request->input('q');
        $orders = collect();

        if ($query) {
            $cleaned = trim($query);
            $orders = Order::with('orderItems')
                ->where('order_number', 'like', '%' . $cleaned . '%')
                ->orWhere('customer_phone', 'like', '%' . $cleaned . '%')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('storefront.order-track', compact('query', 'orders'));
    }

    /**
     * Pencarian cepat lacak pesanan (POST).
     */
    public function trackLookup(Request $request)
    {
        $request->validate([
            'q' => 'required|string|max:50',
        ], [
            'q.required' => 'Masukkan nomor pesanan atau nomor WhatsApp Anda.',
        ]);

        $query = trim($request->input('q'));
        $exactOrder = Order::where('order_number', $query)->first();

        if ($exactOrder) {
            return redirect()->route('order.show', $exactOrder->order_number);
        }

        return redirect()->route('order.track', ['q' => $query]);
    }

    /**
     * Tampilkan nota pesanan untuk dicetak / disimpan (Publik).
     */
    public function nota(Order $order)
    {
        $order->load(['orderItems', 'user']);
        return view('storefront.order-nota', compact('order'));
    }

    /**
     * Riwayat pesanan untuk pelanggan yang login.
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('storefront.my-orders', compact('orders'));
    }

    /**
     * Detail pesanan untuk pelanggan yang login.
     */
    public function myOrderDetail(Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['orderItems.product', 'orderStatusLogs.user']);

        return view('storefront.my-order-detail', compact('order'));
    }

    /**
     * Format nomor pesanan unik: LALA-YYYYMMDD-XXXX
     */
    private function generateOrderNumber(): string
    {
        $dateStr = now()->format('Ymd');
        $prefix = 'LALA-' . $dateStr . '-';

        $lastOrder = Order::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('order_number')
            ->first();

        $seq = 1;
        if ($lastOrder) {
            $parts = explode('-', $lastOrder->order_number);
            $seq = ((int) end($parts)) + 1;
        }

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
