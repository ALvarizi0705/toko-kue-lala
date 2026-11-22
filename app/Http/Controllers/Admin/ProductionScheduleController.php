<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductionScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Hanya pesanan yang sudah diverifikasi (status = diproses)
        $query = Order::with(['orderItems.product', 'user'])
            ->where('status', 'diproses')
            ->orderBy('pickup_date');

        if ($request->filled('date')) {
            $query->whereDate('pickup_date', $request->date);
        }

        $orders = $query->get();

        return view('admin.production-schedule.index', compact('orders'));
    }
}
