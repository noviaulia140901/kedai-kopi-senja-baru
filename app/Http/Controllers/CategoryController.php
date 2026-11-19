<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menus')
            ->orderBy('name')
            ->paginate(10);

        return view('manajer.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        Category::create($validated);

        return redirect()
            ->route('manajer.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $category->update($validated);

        return redirect()
            ->route('manajer.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->menus()->exists()) {
            return back()->with(
                'error',
                'Kategori tidak dapat dihapus karena masih digunakan oleh menu.'
            );
        }

        $category->delete();

        return redirect()
            ->route('manajer.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
