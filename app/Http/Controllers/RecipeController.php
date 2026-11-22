<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\Recipe;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecipeController extends Controller
{
    public function index(Request $request): View
    {
        $menus = Menu::withCount('recipes')
            ->orderBy('name')
            ->get();

        $selectedMenu = null;
        $menuQuery = $request->query('menu');

        if (is_string($menuQuery) && ctype_digit($menuQuery)) {
            $selectedMenu = $menus->firstWhere('id', (int) $menuQuery);
        }

        if ($selectedMenu !== null) {
            $selectedMenu->load(['recipes.ingredient']);
        }

        $ingredients = $selectedMenu === null
            ? collect()
            : Ingredient::orderBy('name')->get();

        return view('manajer.recipes.index', compact(
            'menus',
            'selectedMenu',
            'ingredients',
        ));
    }

    public function store(Request $request): RedirectResponse
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
            ->route('manajer.recipes.index', ['menu' => $validated['menu_id']])
            ->with('success', 'Bahan resep berhasil ditambahkan.');
    }

    public function update(Request $request, Recipe $recipe): RedirectResponse
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
            ->route('manajer.recipes.index', ['menu' => $validated['menu_id']])
            ->with('success', 'Resep berhasil diperbarui.');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $menuId = $recipe->menu_id;
        $recipe->delete();

        return redirect()
            ->route('manajer.recipes.index', ['menu' => $menuId])
            ->with('success', 'Bahan resep berhasil dihapus.');
    }
}
