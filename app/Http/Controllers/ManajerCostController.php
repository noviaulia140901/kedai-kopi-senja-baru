<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class ManajerCostController extends Controller
{
    public function index()
    {
        $menus = Menu::with([
            'recipes.ingredient.prices' => function ($query) {
                $query->orderByDesc('fetched_at')
                    ->orderByDesc('id');
            },
        ])
            ->orderBy('name')
            ->get();

        foreach ($menus as $menu) {
            $totalHpp = 0;
            $lengkap = $menu->recipes->isNotEmpty();
            $tanggalHarga = [];

            foreach ($menu->recipes as $recipe) {
                $ingredient = $recipe->ingredient;
                $harga = $ingredient?->prices->first();

                if (! $harga) {
                    $lengkap = false;

                    continue;
                }

                $biaya = (float) $recipe->quantity
                    * (float) $harga->price_per_unit;

                $totalHpp += $biaya;

                if ($harga->supplier_updated_at) {
                    $tanggalHarga[] = $harga->supplier_updated_at;
                }
            }

            $menu->hpp_total = $lengkap ? $totalHpp : null;

            $menu->margin = $lengkap
                ? (float) $menu->selling_price - $totalHpp
                : null;

            $menu->tanggal_harga = count($tanggalHarga) > 0
                ? collect($tanggalHarga)->max()
                : null;

            $menu->hpp_lengkap = $lengkap;
        }

        return view('manajer.costs.index', compact('menus'));
    }
}
