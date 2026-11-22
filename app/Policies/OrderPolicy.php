<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Pelanggan hanya boleh melihat pesanannya sendiri.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->role === 'pelanggan') {
            return $user->id === $order->user_id;
        }
        // Pemilik dan staf bisa lihat semua
        return in_array($user->role, ['pemilik', 'staf_kasir', 'staf_produksi']);
    }

    public function uploadPayment(User $user, Order $order): bool
    {
        return $user->id === $order->user_id
            && in_array($order->status, ['menunggu_pembayaran', 'menunggu_verifikasi']);
    }
}
