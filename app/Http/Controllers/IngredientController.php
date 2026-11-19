<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::orderBy('name')
            ->paginate(10);

        return view('manajer.ingredients.index', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:ingredients,name'],
            'base_unit' => ['required', Rule::in(['g', 'ml', 'pcs'])],
            'current_stock' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        Ingredient::create($validated);

        return redirect()
            ->route('manajer.ingredients.index')
            ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('ingredients', 'name')->ignore($ingredient->id),
            ],
            'base_unit' => ['required', Rule::in(['g', 'ml', 'pcs'])],
            'current_stock' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $ingredient->update($validated);

        return redirect()
            ->route('manajer.ingredients.index')
            ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient->recipes()->exists()) {
            return redirect()
                ->route('manajer.ingredients.index')
                ->with('error', 'Bahan baku tidak dapat dihapus karena masih digunakan dalam resep.');
        }

        $ingredient->delete();

        return redirect()
            ->route('manajer.ingredients.index')
            ->with('success', 'Bahan baku berhasil dihapus.');
    }
}
