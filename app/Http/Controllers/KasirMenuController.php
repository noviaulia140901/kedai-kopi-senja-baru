<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class KasirMenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category', 'recipes.ingredient')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        foreach ($menus as $menu) {
            if ($menu->recipes->isEmpty()) {
                $status = 'Belum ada resep';
            } else {
                $habis = $menu->recipes->contains(function ($recipe) {
                    return ! $recipe->ingredient
                        || (float) $recipe->ingredient->current_stock
                        < (float) $recipe->quantity;
                });

                $status = $habis ? 'Habis' : 'Tersedia';
            }

            $menu->setAttribute('availability_status', $status);
        }

        $orders = Order::with('items')
            ->where('order_channel', 'kasir')
            ->latest('ordered_at')
            ->paginate(10);

        return view('kasir.menus.index', compact('menus', 'orders'));
    }

    public function orderHistory(Request $request)
    {
        $status = $request->query('status');
        if (! in_array($status, ['baru', 'diproses', 'selesai'], true)) {
            $status = null;
        }

        $ordersQuery = Order::with('items')
            ->where('order_channel', 'kasir');

        if ($status !== null) {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery
            ->latest('ordered_at')
            ->paginate(10)
            ->withQueryString();

        return view('kasir.orders.index', compact('orders'));
    }

    public function store(Request $request, OrderService $orderService)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'integer', 'exists:menus,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['nullable', 'in:tunai,qris'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = $orderService->create(
            $data,
            $request->user(),
            'kasir'
        );

        return redirect()
            ->route('kasir.orders.receipt', $order->id);
    }

    public function updateStatus(
        Request $request,
        int $order,
        OrderService $orderService
    ) {
        $data = $request->validate([
            'status' => ['required', 'in:diproses,selesai'],
        ]);

        Order::where('order_channel', 'kasir')->findOrFail($order);

        $orderService->changeStatus(
            $order,
            $request->user(),
            $data['status']
        );

        return redirect()
            ->route('kasir.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function cancel(
        Request $request,
        int $order,
        OrderService $orderService
    ) {
        Order::where('order_channel', 'kasir')->findOrFail($order);

        $orderService->cancel(
            $order,
            $request->user()
        );

        return redirect()
            ->route('kasir.orders.index')
            ->with('success', 'Pesanan dibatalkan dan stok dikembalikan.');
    }

    public function receipt(int $order)
    {
        $order = Order::with(['items', 'cashier'])
            ->where('order_channel', 'kasir')
            ->findOrFail($order);

        return view('kasir.orders.receipt', compact('order'));
    }
}
