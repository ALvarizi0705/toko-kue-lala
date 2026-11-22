<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentVerificationController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->where('status', 'menunggu_verifikasi')
            ->orderBy('updated_at')
            ->paginate(10);

        return view('admin.payment-verification.index', compact('orders'));
    }

    public function approve(Order $order)
    {
        if ($order->status !== 'menunggu_verifikasi') {
            return back()->with('error', 'Status pesanan bukan menunggu verifikasi.');
        }

        $oldStatus = $order->status;
        $order->update([
            'status' => 'diproses',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'payment_reject_reason' => null,
        ]);

        $order->orderStatusLogs()->create([
            'from_status' => $oldStatus,
            'to_status' => 'diproses',
            'user_id' => auth()->id(),
            'note' => 'Pembayaran diverifikasi dan diterima oleh ' . auth()->user()->name,
        ]);

        return back()->with('success', 'Pembayaran pesanan #' . $order->order_number . ' berhasil diverifikasi.');
    }

    public function reject(Request $request, Order $order)
    {
        if ($order->status !== 'menunggu_verifikasi') {
            return back()->with('error', 'Status pesanan bukan menunggu verifikasi.');
        }

        $request->validate([
            'payment_reject_reason' => 'required|string|max:255',
        ], [
            'payment_reject_reason.required' => 'Alasan penolakan bukti pembayaran wajib diisi.',
        ]);

        $oldStatus = $order->status;
        $order->update([
            'status' => 'menunggu_pembayaran',
            'payment_reject_reason' => $request->payment_reject_reason,
            'payment_proof' => null,
        ]);

        $order->orderStatusLogs()->create([
            'from_status' => $oldStatus,
            'to_status' => 'menunggu_pembayaran',
            'user_id' => auth()->id(),
            'note' => 'Pembayaran ditolak: ' . $request->payment_reject_reason,
        ]);

        return back()->with('success', 'Bukti pembayaran ditolak. Pelanggan dapat mengunggah bukti ulang.');
    }
}
