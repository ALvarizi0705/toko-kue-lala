<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\StockUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockUsageController extends Controller
{
    public function index()
    {
        $stockUsages = StockUsage::with(['ingredient', 'user'])
            ->latest('date')
            ->paginate(15);

        return view('admin.stock-usages.index', compact('stockUsages'));
    }

    public function create()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        return view('admin.stock-usages.form', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'qty' => 'required|numeric|min:0.001',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
        ], [
            'ingredient_id.required' => 'Bahan baku wajib dipilih.',
            'qty.required' => 'Jumlah pemakaian wajib diisi.',
            'date.required' => 'Tanggal pemakaian wajib diisi.',
        ]);

        $ingredient = Ingredient::findOrFail($request->ingredient_id);

        if ($request->qty > $ingredient->stock) {
            return back()->withInput()->with('error', 'Jumlah pemakaian (' . $request->qty . ' ' . $ingredient->unit . ') melebihi stok yang tersedia (' . $ingredient->stock . ' ' . $ingredient->unit . ').');
        }

        DB::transaction(function () use ($request, $ingredient) {
            StockUsage::create([
                'ingredient_id' => $ingredient->id,
                'qty' => $request->qty,
                'date' => $request->date,
                'note' => $request->note,
                'user_id' => auth()->id(),
            ]);

            $ingredient->decrement('stock', $request->qty);
        });

        return redirect()->route('admin.pemakaian-bahan.index')->with('success', 'Pemakaian bahan berhasil dicatat.');
    }
}
