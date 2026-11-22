<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DirectSale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectSaleController extends Controller
{
    public function index()
    {
        $sales = DirectSale::with(['user', 'directSaleItems'])
            ->latest('sold_at')
            ->paginate(15);

        return view('admin.direct-sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.direct-sales.form', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:tunai,transfer,qris',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ], [
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'items.required' => 'Minimal pilih 1 produk yang terjual.',
        ]);

        $directSale = null;

        DB::transaction(function () use ($request, &$directSale) {
            $total = 0;
            $saleNumber = $this->generateSaleNumber();

            $directSale = DirectSale::create([
                'sale_number' => $saleNumber,
                'user_id' => auth()->id(),
                'payment_method' => $request->payment_method,
                'total' => 0,
                'sold_at' => now(),
            ]);

            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                if (!$product) continue;

                $qty = (int) $itemData['qty'];
                $unitPrice = (float) $product->price;
                $subtotal = $unitPrice * $qty;
                $total += $subtotal;

                $directSale->directSaleItems()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $unitPrice,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                ]);
            }

            $directSale->update(['total' => $total]);
        });

        return redirect()->route('admin.penjualan-langsung.show', $directSale)
            ->with('success', 'Penjualan langsung #' . $directSale->sale_number . ' berhasil disimpan.');
    }

    public function show(DirectSale $penjualan_langsung)
    {
        $sale = $penjualan_langsung;
        $sale->load(['user', 'directSaleItems.product']);

        return view('admin.direct-sales.show', compact('sale'));
    }

    public function cetak(DirectSale $penjualan_langsung, Request $request)
    {
        $sale = $penjualan_langsung;
        $sale->load(['user', 'directSaleItems']);
        $format = $request->query('format', 'a5'); // a5 atau 58mm

        return view('admin.direct-sales.print', compact('sale', 'format'));
    }

    private function generateSaleNumber(): string
    {
        $prefix = 'DS-' . now()->format('Ymd') . '-';
        $lastSale = DirectSale::where('sale_number', 'like', $prefix . '%')
            ->orderByDesc('sale_number')
            ->first();

        $seq = 1;
        if ($lastSale) {
            $parts = explode('-', $lastSale->sale_number);
            $seq = ((int) end($parts)) + 1;
        }

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
