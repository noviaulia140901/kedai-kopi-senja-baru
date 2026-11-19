<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManajerDashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $totalMenuAktif = DB::table('menus')
            ->where('is_active', true)
            ->count();

        $totalBahanBaku = DB::table('ingredients')
            ->where('is_active', true)
            ->count();

        $bahanMenipis = DB::table('ingredients')
            ->where('is_active', true)
            ->whereColumn('current_stock', '<=', "minimum_stock")
            ->count();

        $pesananHariIni = DB::table('orders')
            ->whereDate('ordered_at', $today)
            ->where('status', "!=", 'dibatalkan')
            ->count();

        $penjualanHariIni = DB::table('orders')
            ->whereDate('ordered_at', $today)
            ->where('status', 'selesai')
            ->sum('total');

        $pesananTerbaru = DB::table('orders')
            ->leftJoin('users', 'orders.customer_id', '=', 'users.id')
            ->select(
                 'orders.id',
                'orders.order_code',
                'orders.status',
                'orders.total',
                'orders.ordered_at',
                'users.name as customer_name'
            )
            ->orderByDesc('orders.ordered_at')
            ->limit(5)
            ->get();

        return view('manajer.dashboard', compact(
            'totalMenuAktif',
            'totalBahanBaku',
            'bahanMenipis',
            'pesananHariIni',
            'penjualanHariIni',
            'pesananTerbaru'
        ));
    }
}
