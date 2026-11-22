<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == '1');
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.min' => 'Harga harus lebih dari 0.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = 'prod_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('products', $fileName, 'public');
        }

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'photo' => $photoPath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $produk)
    {
        $product = $produk;
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $produk)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'price.min' => 'Harga harus lebih dari 0.',
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('photo')) {
            if ($produk->photo && Storage::disk('public')->exists($produk->photo)) {
                Storage::disk('public')->delete($produk->photo);
            }

            $file = $request->file('photo');
            $fileName = 'prod_' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $data['photo'] = $file->storeAs('products', $fileName, 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $statusText = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Status produk berhasil {$statusText}.");
    }

    public function destroy(Product $produk)
    {
        if ($produk->photo && Storage::disk('public')->exists($produk->photo)) {
            Storage::disk('public')->delete($produk->photo);
        }

        $produk->delete();
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
