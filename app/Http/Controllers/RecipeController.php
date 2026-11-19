<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with(['menu', 'ingredient'])
            ->orderBy('menu_id')
            ->paginate(10);

        $menus = Menu::orderBy('name')->get();

        $ingredients = Ingredient::orderBy('name')->get();

        return view('manajer.recipes.index', compact(
            'recipes',
            'menus',
            'ingredients'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'ingredient_id' => [
                'required',
                'exists:ingredients,id',
                Rule::unique('recipes', 'ingredient_id')
                    ->where('menu_id', $request->menu_id),
            ],
            'quantity' => ['required', 'numeric', 'gt:0'],
        ]);

        Recipe::create($validated);

        return redirect()
            ->route('manajer.recipes.index')
            ->with('success', 'Bahan resep berhasil ditambahkan.');
    }

    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'ingredient_id' => [
                'required',
                'exists:ingredients,id',
                Rule::unique('recipes', 'ingredient_id')
                    ->where('menu_id', $request->menu_id)
                    ->ignore($recipe->id),
            ],
            'quantity' => ['required', 'numeric', 'gt:0'],
        ]);

        $recipe->update($validated);

        return redirect()
            ->route('manajer.recipes.index')
            ->with('success', 'Resep berhasil diperbarui.');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return redirect()
            ->route('manajer.recipes.index')
            ->with('success', 'Bahan resep berhasil dihapus.');
    }
}
