<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->q . '%')
                  ->orWhereHas('user', function ($uq) use ($request) {
                      $uq->where('name', 'like', '%' . $request->q . '%')
                         ->orWhere('phone', 'like', '%' . $request->q . '%');
                  });
            });
        }

        if ($request->filled('pickup_date')) {
            $query->whereDate('pickup_date', $request->pickup_date);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $pesanan)
    {
        $order = $pesanan;
        $order->load(['user', 'orderItems.product', 'orderStatusLogs.user', 'verifiedByUser']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:menunggu_pembayaran,menunggu_verifikasi,diproses,siap_diambil,selesai,dibatalkan',
        ]);

        $currentStatus = $order->status;
        $nextStatus = $request->status;

        // Validasi urutan alur:
        // menunggu_pembayaran -> menunggu_verifikasi -> diproses -> siap_diambil -> selesai
        $allowedTransitions = [
            'menunggu_pembayaran' => ['menunggu_verifikasi', 'diproses', 'dibatalkan'],
            'menunggu_verifikasi' => ['diproses', 'menunggu_pembayaran', 'dibatalkan'],
            'diproses' => ['siap_diambil', 'dibatalkan'],
            'siap_diambil' => ['selesai'],
            'selesai' => [],
            'dibatalkan' => [],
        ];

        if (auth()->user()->role === 'staf_produksi' && $nextStatus === 'dibatalkan') {
            return back()->with('error', 'Staf produksi tidak diperbolehkan membatalkan pesanan.');
        }

        if (!in_array($nextStatus, $allowedTransitions[$currentStatus] ?? [])) {
            return back()->with('error', 'Perubahan status dari ' . $order->status_label . ' tidak valid.');
        }

        $order->update(['status' => $nextStatus]);

        $order->orderStatusLogs()->create([
            'from_status' => $currentStatus,
            'to_status' => $nextStatus,
            'user_id' => auth()->id(),
            'note' => $request->note ?? ('Status diubah oleh ' . auth()->user()->name),
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi ' . $order->status_label . '.');
    }

    public function cancel(Request $request, Order $order)
    {
        if (auth()->user()->role === 'staf_produksi') {
            abort(403, 'Staf produksi tidak memiliki izin membatalkan pesanan.');
        }

        if (in_array($order->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Pesanan yang sudah selesai atau dibatalkan tidak dapat diubah lagi.');
        }

        $request->validate([
            'cancel_reason' => 'required|string|max:255',
        ], [
            'cancel_reason.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => 'dibatalkan']);

        $order->orderStatusLogs()->create([
            'from_status' => $oldStatus,
            'to_status' => 'dibatalkan',
            'user_id' => auth()->id(),
            'note' => 'Dibatalkan: ' . $request->cancel_reason,
        ]);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function cetak(Order $order)
    {
        $order->load(['user', 'orderItems']);
        return view('admin.orders.print', compact('order'));
    }

    public function whatsapp(Order $order)
    {
        $phone = preg_replace('/[^0-9]/', '', $order->user->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $shopName = Setting::get('shop_name', 'Toko Kue Lala');
        $pickupHours = Setting::get('pickup_hours', '08.00 - 20.00 WIB');
        $address = Setting::get('address', 'Jl. Matraman Dalam III No. 26B, Jakarta Timur');

        $message = "Halo kak {$order->user->name}, pesanan kue Anda di *{$shopName}* dengan nomor *{$order->order_number}* sudah *SIAP DIAMBIL*!\n\n"
                 . "📍 Alamat: {$address}\n"
                 . "⏰ Jam Buka: {$pickupHours}\n\n"
                 . "Mohon tunjukkan nomor pesanan saat pengambilan. Terima kasih telah mempercayai Toko Kue Lala! 🎂✨";

        $url = 'https://wa.me/' . $phone . '?text=' . urlencode($message);

        return redirect()->away($url);
    }
}
