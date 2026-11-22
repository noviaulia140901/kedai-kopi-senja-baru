<?php

namespace App\Http\Controllers;

use App\Models\Order;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::where('order_channel', 'kasir');

        $totalOrders = (clone $orders)->count();

        $newOrders = (clone $orders)
            ->where('status', 'baru')
            ->count();

        $processingOrders = (clone $orders)
            ->where('status', 'diproses')
            ->count();

        $completedOrders = (clone $orders)
            ->where('status', 'selesai')
            ->count();

        $latestOrders = Order::with('items')
            ->where('order_channel', 'kasir')
            ->latest('ordered_at')
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact(
            'totalOrders',
            'newOrders',
            'processingOrders',
            'completedOrders',
            'latestOrders'
        ));
    }
}
