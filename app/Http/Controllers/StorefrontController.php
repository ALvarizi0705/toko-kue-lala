<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    /**
     * Halaman Beranda: hero, produk unggulan/terbaru, kategori, cara memesan.
     */
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)->get();

        return view('storefront.home', compact('featuredProducts', 'categories'));
    }

    /**
     * Katalog: grid produk aktif, pencarian nama, filter kategori, pagination.
     */
    public function catalog(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }

        $products = $query->orderBy('name')->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        $selectedCategory = $request->kategori;

        return view('storefront.catalog', compact('products', 'categories', 'selectedCategory'));
    }

    /**
     * Detail produk.
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('category');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('storefront.product-detail', compact('product', 'relatedProducts'));
    }
}
