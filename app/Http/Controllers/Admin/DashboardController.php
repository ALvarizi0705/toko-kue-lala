<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DirectSale;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $pendingOrders = Order::where('status', 'menunggu_pembayaran')->count();
        $pendingVerification = Order::where('status', 'menunggu_verifikasi')->count();

        $processingToday = Order::where('status', 'diproses')->count();
        $readyOrders = Order::where('status', 'siap_diambil')->count();

        $upcomingOrders = Order::with(['user', 'orderItems'])
            ->whereIn('status', ['menunggu_pembayaran', 'menunggu_verifikasi', 'diproses', 'siap_diambil'])
            ->orderBy('pickup_date')
            ->take(10)
            ->get();

        $salesToday = null;
        if ($user->role === 'pemilik') {
            $onlineSales = Order::where('status', 'selesai')
                ->whereDate('updated_at', Carbon::today())
                ->sum('total');

            $directSales = DirectSale::whereDate('sold_at', Carbon::today())
                ->sum('total');

            $salesToday = $onlineSales + $directSales;
        }

        return view('admin.dashboard', compact(
            'pendingOrders',
            'pendingVerification',
            'processingToday',
            'readyOrders',
            'upcomingOrders',
            'salesToday'
        ));
    }
}
