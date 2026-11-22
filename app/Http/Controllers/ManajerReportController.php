<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManajerReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $tanggalMulai = $validated['tanggal_mulai'] ?? now()->startOfMonth()->toDateString();
        $tanggalAkhir = $validated['tanggal_akhir'] ?? now()->toDateString();

        // Penjualan per menu berdasarkan periode
        $penjualan = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'selesai')
            ->whereDate('orders.ordered_at', '>=', $tanggalMulai)
            ->whereDate('orders.ordered_at', '<=', $tanggalAkhir)
            ->select(
                'order_items.menu_id',
                'order_items.menu_name',
                DB::raw('SUM(order_items.quantity) as total_terjual'),
                DB::raw('SUM(order_items.subtotal) as total_penjualan')
            )
            ->groupBy('order_items.menu_id', 'order_items.menu_name')
            ->orderBy('order_items.menu_name')
            ->get();

        // Daftar bahan yang stoknya menipis
        $bahanMenipis = Ingredient::where('is_active', true)
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->orderBy('name')
            ->get();

        // Margin setiap menu menggunakan perhitungan HPP yang sama
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

            foreach ($menu->recipes as $recipe) {
                $harga = $recipe->ingredient?->prices->first();

                if (! $harga) {
                    $lengkap = false;

                    continue;
                }

                $totalHpp += (float) $recipe->quantity
                    * (float) $harga->price_per_unit;
            }

            $menu->hpp_lengkap = $lengkap;
            $menu->margin = $lengkap
                ? (float) $menu->selling_price - $totalHpp
                : null;
        }

        return view('manajer.reports.index', compact(
            'tanggalMulai',
            'tanggalAkhir',
            'penjualan',
            'bahanMenipis',
            'menus'
        ));
    }
}
