<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang belanja dari session.
     */
    public function index()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum('subtotal');
        return view('storefront.cart', compact('cart', 'total'));
    }

    /**
     * Tambah produk ke keranjang.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1|max:100',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            return back()->with('error', 'Produk sedang tidak tersedia.');
        }

        $cart = session('cart', []);
        $key = 'item_' . $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $request->qty;
            $cart[$key]['subtotal'] = $cart[$key]['qty'] * $cart[$key]['price'];
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => (float)$product->price,
                'qty' => (int)$request->qty,
                'subtotal' => (float)($product->price * $request->qty),
                'photo' => $product->photo_url,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Produk "' . $product->name . '" ditambahkan ke keranjang.');
    }

    /**
     * Update kuantitas item.
     */
    public function update(Request $request, string $rowId)
    {
        $request->validate([
            'qty' => 'required|integer|min:1|max:100',
        ]);

        $cart = session('cart', []);

        if (isset($cart[$rowId])) {
            $cart[$rowId]['qty'] = (int)$request->qty;
            $cart[$rowId]['subtotal'] = $cart[$rowId]['price'] * $cart[$rowId]['qty'];
            session(['cart' => $cart]);
            return back()->with('success', 'Jumlah pesanan diperbarui.');
        }

        return back()->with('error', 'Item tidak ditemukan di keranjang.');
    }

    /**
     * Hapus item dari keranjang.
     */
    public function remove(string $rowId)
    {
        $cart = session('cart', []);
        if (isset($cart[$rowId])) {
            unset($cart[$rowId]);
            session(['cart' => $cart]);
            return back()->with('success', 'Item dihapus dari keranjang.');
        }

        return back()->with('error', 'Item tidak ditemukan.');
    }

    /**
     * Kosongkan keranjang.
     */
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
