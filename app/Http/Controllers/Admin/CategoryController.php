<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah terdaftar.',
        ]);

        Category::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $kategori)
    {
        $category = $kategori;
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $kategori)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $kategori->id,
        ]);

        $kategori->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $kategori)
    {
        if ($kategori->products()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
        }

        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
