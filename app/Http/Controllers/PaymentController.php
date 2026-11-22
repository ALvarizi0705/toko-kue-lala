<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman instruksi pembayaran & upload bukti.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $settings = [
            'bank_bca' => Setting::get('bank_bca', '1234567890 (a/n Lala Komalasari)'),
            'dana' => Setting::get('dana', '08123456789 (Toko Kue Lala)'),
            'gopay' => Setting::get('gopay', '08123456789 (Toko Kue Lala)'),
            'qris_image' => Setting::get('qris_image'),
        ];

        return view('storefront.payment', compact('order', 'settings'));
    }

    /**
     * Upload bukti transfer (manual verification).
     */
    public function upload(Request $request, Order $order)
    {
        $this->authorize('uploadPayment', $order);

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.mimes' => 'Format file yang diterima: JPG, JPEG, PNG, WEBP.',
            'payment_proof.max' => 'Ukuran maksimal 2 MB.',
        ]);

        $file = $request->file('payment_proof');
        $fileName = 'payment_' . Str::random(24) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payments', $fileName, 'public');

        $oldStatus = $order->status;
        $order->update([
            'payment_proof' => $path,
            'status' => 'menunggu_verifikasi',
            'payment_reject_reason' => null,
        ]);

        $order->orderStatusLogs()->create([
            'from_status' => $oldStatus,
            'to_status' => 'menunggu_verifikasi',
            'user_id' => auth()->id(),
            'note' => 'Bukti pembayaran diunggah oleh pelanggan.',
        ]);

        return redirect()->route('orders.my.detail', $order)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi kasir.');
    }
}
