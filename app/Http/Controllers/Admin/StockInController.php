<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $stockIns = StockIn::with(['ingredient', 'user'])
            ->latest('date')
            ->paginate(15);

        return view('admin.stock-ins.index', compact('stockIns'));
    }

    public function create()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        return view('admin.stock-ins.form', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'qty' => 'required|numeric|min:0.001',
            'date' => 'required|date',
            'supplier' => 'nullable|string|max:255',
        ], [
            'ingredient_id.required' => 'Bahan baku wajib dipilih.',
            'qty.required' => 'Jumlah barang masuk wajib diisi.',
            'qty.min' => 'Jumlah harus lebih besar dari 0.',
            'date.required' => 'Tanggal wajib diisi.',
        ]);

        DB::transaction(function () use ($request) {
            $ingredient = Ingredient::lockForUpdate()->find($request->ingredient_id);

            StockIn::create([
                'ingredient_id' => $ingredient->id,
                'qty' => $request->qty,
                'supplier' => $request->supplier,
                'date' => $request->date,
                'user_id' => auth()->id(),
            ]);

            $ingredient->increment('stock', $request->qty);
        });

        return redirect()->route('admin.barang-masuk.index')->with('success', 'Pencatatan barang masuk berhasil.');
    }
}
