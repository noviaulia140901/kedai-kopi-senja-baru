<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockEntryController extends Controller
{
    public function index()
    {
        $stockEntries = StockEntry::with(['ingredient', 'enteredBy'])
            ->latest('entry_date')
            ->latest('id')
            ->paginate(10);

        $ingredients = Ingredient::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('manajer.stock-entries.index', compact(
            'stockEntries',
            'ingredients'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ingredient_id' => [
                'required',
                'exists:ingredients,id',
            ],
            'quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'supplier_name' => [
                'nullable',
                'string',
                'max:150',
            ],
            'entry_date' => [
                'required',
                'date',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $ingredient = Ingredient::whereKey($validated['ingredient_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if (! $ingredient->is_active) {
                abort(422, 'Bahan baku ini sedang tidak aktif.');
            }

            $totalPrice = round(
                $validated['quantity'] * $validated['unit_price'],
                2
            );

            StockEntry::create([
                'ingredient_id' => $ingredient->id,
                'entered_by' => auth()->id(),
                'quantity' => $validated['quantity'],
                'unit_price' => $validated['unit_price'],
                'total_price' => $totalPrice,
                'supplier_name' => $validated['supplier_name'] ?? null,
                'entry_date' => $validated['entry_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $ingredient->increment(
                'current_stock',
                $validated['quantity']
            );
        });

        return redirect()
            ->route('manajer.stock-entries.index')
            ->with('success', 'Stok bahan baku berhasil ditambahkan.');
    }
}
