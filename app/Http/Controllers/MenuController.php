<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')
            ->orderBy('name')
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        return view('manajer.menus.index', compact('menus', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($validated);

        return redirect()
            ->route('manajer.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        } else {
            unset($validated['image']);
        }

        $menu->update($validated);

        return redirect()
            ->route('manajer.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }
    public function destroy(Menu $menu)
    {
        $sudahDipesan = DB::table('order_items')
            ->where('menu_id', $menu->id)
            ->exists();

        if ($sudahDipesan) {
            return back()->with(
                'error',
                'Menu tidak dapat dihapus karena sudah memiliki riwayat pesanan.'
            );
        }

        $menu->delete();

        return redirect()
            ->route('manajer.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

}
