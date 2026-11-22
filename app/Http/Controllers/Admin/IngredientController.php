<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $query = Ingredient::query();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('code', 'like', '%' . $request->q . '%');
        }

        $ingredients = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.ingredients.index', compact('ingredients'));
    }

    public function warnings()
    {
        $ingredients = Ingredient::whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->paginate(15);

        return view('admin.ingredients.warnings', compact('ingredients'));
    }

    public function create()
    {
        return view('admin.ingredients.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:ingredients,code',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
        ], [
            'code.required' => 'Kode bahan wajib diisi.',
            'code.unique' => 'Kode bahan sudah digunakan.',
            'name.required' => 'Nama bahan wajib diisi.',
            'unit.required' => 'Satuan bahan wajib diisi.',
        ]);

        Ingredient::create($request->only(['code', 'name', 'unit', 'stock', 'min_stock']));

        return redirect()->route('admin.bahan.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(Ingredient $bahan)
    {
        $ingredient = $bahan;
        return view('admin.ingredients.form', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $bahan)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:ingredients,code,' . $bahan->id,
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|numeric|min:0',
        ]);

        $bahan->update($request->only(['code', 'name', 'unit', 'min_stock']));

        return redirect()->route('admin.bahan.index')->with('success', 'Data bahan baku berhasil diperbarui.');
    }

    public function destroy(Ingredient $bahan)
    {
        if ($bahan->stockIns()->exists() || $bahan->stockUsages()->exists()) {
            return back()->with('error', 'Bahan tidak dapat dihapus karena sudah memiliki riwayat mutasi stok.');
        }

        $bahan->delete();
        return redirect()->route('admin.bahan.index')->with('success', 'Bahan baku berhasil dihapus.');
    }
}
