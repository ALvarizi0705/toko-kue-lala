<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = StockAdjustment::with(['ingredient', 'user'])
            ->latest('date')
            ->paginate(15);

        return view('admin.stock-adjustments.index', compact('adjustments'));
    }

    public function create()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        return view('admin.stock-adjustments.form', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'difference' => 'required|numeric',
            'reason' => 'required|string|max:500',
            'date' => 'required|date',
        ], [
            'ingredient_id.required' => 'Bahan baku wajib dipilih.',
            'difference.required' => 'Nilai selisih stok wajib diisi.',
            'reason.required' => 'Alasan koreksi stok wajib diisi.',
            'date.required' => 'Tanggal koreksi wajib diisi.',
        ]);

        $ingredient = Ingredient::findOrFail($request->ingredient_id);

        if ($ingredient->stock + $request->difference < 0) {
            return back()->withInput()->with('error', 'Koreksi akan membuat stok menjadi negatif. Mohon periksa kembali.');
        }

        DB::transaction(function () use ($request, $ingredient) {
            StockAdjustment::create([
                'ingredient_id' => $ingredient->id,
                'difference' => $request->difference,
                'reason' => $request->reason,
                'user_id' => auth()->id(),
                'date' => $request->date,
            ]);

            $ingredient->stock += $request->difference;
            $ingredient->save();
        });

        return redirect()->route('admin.koreksi-stok.index')->with('success', 'Koreksi stok berhasil dicatat.');
    }
}
