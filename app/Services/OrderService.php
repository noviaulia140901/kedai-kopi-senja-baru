<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\IngredientPrice;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function create(
        array $data,
        User $actor,
        string $channel = 'kasir',
        ?int $customerId = null
    ): Order {
        return DB::transaction(function () use ($data, $actor, $channel, $customerId) {

            if (!in_array($channel, ['kasir', 'apk'], true)) {
                throw ValidationException::withMessages([
                    'items' => 'Jenis pemesanan tidak valid.',
                ]);
            }

            $quantities = [];

            foreach ($data['items'] ?? [] as $item) {
                $menuId = (int) ($item['menu_id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);

                if ($menuId < 1 || $quantity < 1) {
                    throw ValidationException::withMessages([
                        'items' => 'Menu dan jumlah pesanan harus valid.',
                    ]);
                }

                $quantities[$menuId] = ($quantities[$menuId] ?? 0) + $quantity;
            }

            if (empty($quantities)) {
                throw ValidationException::withMessages([
                    'items' => 'Pilih minimal satu menu.',
                ]);
            }

            $menus = Menu::with('recipes.ingredient')
                ->whereIn('id', array_keys($quantities))
                ->get()
                ->keyBy('id');

            $required = [];

            foreach ($quantities as $menuId => $quantity) {
                $menu = $menus->get($menuId);

                if (!$menu || !$menu->is_active) {
                    throw ValidationException::withMessages([
                        'items' => 'Menu tidak ditemukan atau sudah tidak aktif.',
                    ]);
                }

                if ($menu->recipes->isEmpty()) {
                    throw ValidationException::withMessages([
                        'items' => 'Menu ' . $menu->name . ' belum memiliki resep.',
                    ]);
                }

                foreach ($menu->recipes as $recipe) {
                    if (!$recipe->ingredient || !$recipe->ingredient->is_active) {
                        throw ValidationException::withMessages([
                            'items' => 'Bahan resep menu tidak tersedia.',
                        ]);
                    }

                    $ingredientId = $recipe->ingredient_id;

                    $used = (int) round(
                        (float) $recipe->quantity * $quantity * 1000
                    );

                    $required[$ingredientId] =
                        ($required[$ingredientId] ?? 0) + $used;
                }
            }

            $ingredients = Ingredient::whereIn('id', array_keys($required))
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $shortages = [];

            foreach ($required as $ingredientId => $needed) {
                $ingredient = $ingredients->get($ingredientId);

                if (!$ingredient) {
                    continue;
                }

                $available = (int) round(
                    (float) $ingredient->current_stock * 1000
                );

                if ($available < $needed) {
                    $shortages[] = $ingredient->name
                        . ' (butuh '
                        . number_format($needed / 1000, 3, ',', '.')
                        . ' ' . $ingredient->base_unit
                        . ', tersedia '
                        . number_format($available / 1000, 3, ',', '.')
                        . ' ' . $ingredient->base_unit . ')';
                }
            }

            if (!empty($shortages)) {
                throw ValidationException::withMessages([
                    'items' => 'Stok bahan tidak mencukupi: '
                        . implode('; ', $shortages),
                ]);
            }

            $subtotal = 0;

            foreach ($quantities as $menuId => $quantity) {
                $subtotal +=
                    (float) $menus->get($menuId)->selling_price * $quantity;
            }

            $subtotal = round($subtotal, 2);
            $serviceCharge = round($subtotal * 0.05, 2);
            $tax = round(($subtotal + $serviceCharge) * 0.10, 2);
            $total = round($subtotal + $serviceCharge + $tax, 2);

            $order = Order::create([
                'order_code' => 'KS-' . now()->format('ymd')
                    . '-' . strtoupper(Str::random(6)),
                'customer_id' => $customerId,
                'cashier_id' => $channel === 'kasir' ? $actor->id : null,
                'order_channel' => $channel,
                'table_number' => $data['table_number'] ?? null,
                'status' => 'baru',
                'payment_method' => $data['payment_method'] ?? null,
                'subtotal' => $subtotal,
                'service_charge' => $serviceCharge,
                'tax' => $tax,
                'total' => $total,
                'notes' => $data['notes'] ?? null,
                'ordered_at' => now(),
            ]);

            $ingredientPrices = IngredientPrice::whereIn(
                'ingredient_id',
                array_keys($required)
            )
                ->orderByDesc('id')
                ->get()
                ->unique('ingredient_id')
                ->keyBy('ingredient_id');

            foreach ($quantities as $menuId => $quantity) {
                $menu = $menus->get($menuId);
                $unitPrice = (float) $menu->selling_price;

                $item = $order->items()->create([
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'subtotal' => round($unitPrice * $quantity, 2),
                ]);

                foreach ($menu->recipes as $recipe) {
                    $ingredient = $ingredients->get($recipe->ingredient_id);

                    $used = round(
                        (float) $recipe->quantity * $quantity,
                        3
                    );

                    $unitCost = (float) (
                        $ingredientPrices->get($ingredient->id)
                            ?->price_per_unit ?? 0
                    );

                    $item->ingredients()->create([
                        'ingredient_id' => $ingredient->id,
                        'ingredient_name' => $ingredient->name,
                        'quantity_used' => $used,
                        'unit_cost' => $unitCost,
                        'total_cost' => round($used * $unitCost, 2),
                    ]);
                }
            }

            foreach ($required as $ingredientId => $needed) {
                $ingredient = $ingredients->get($ingredientId);

                $before = (int) round(
                    (float) $ingredient->current_stock * 1000
                );

                $after = $before - $needed;

                $ingredient->current_stock = number_format(
                    $after / 1000,
                    3,
                    '.',
                    ''
                );

                $ingredient->save();

                StockMovement::create([
                    'ingredient_id' => $ingredient->id,
                    'user_id' => $actor->id,
                    'order_id' => $order->id,
                    'movement_type' => 'pemakaian',
                    'quantity' => number_format($needed / 1000, 3, '.', ''),
                    'stock_before' => number_format($before / 1000, 3, '.', ''),
                    'stock_after' => number_format($after / 1000, 3, '.', ''),
                    'notes' => 'Pemakaian bahan untuk pesanan '
                        . $order->order_code,
                ]);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $actor->id,
                'old_status' => null,
                'new_status' => 'baru',
                'notes' => 'Pesanan dibuat.',
            ]);

            return $order->load('items.ingredients', 'statusHistories');
        });
    }

    public function changeStatus(
        int $orderId,
        User $actor,
        string $newStatus
    ): Order {
        return DB::transaction(function () use ($orderId, $actor, $newStatus) {

            if ($actor->role !== 'kasir') {
                throw ValidationException::withMessages([
                    'status' => 'Hanya Kasir yang boleh mengubah status.',
                ]);
            }

            $order = Order::lockForUpdate()->findOrFail($orderId);

            $allowed = [
                'baru' => 'diproses',
                'diproses' => 'selesai',
            ];

            if (($allowed[$order->status] ?? null) !== $newStatus) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status harus berurutan.',
                ]);
            }

            $oldStatus = $order->status;

            $order->status = $newStatus;
            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $actor->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => 'Status pesanan diperbarui.',
            ]);

            return $order;
        });
    }

    public function cancel(
        int $orderId,
        User $actor,
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($orderId, $actor, $notes) {

            if ($actor->role !== 'kasir') {
                throw ValidationException::withMessages([
                    'status' => 'Hanya Kasir yang boleh membatalkan pesanan.',
                ]);
            }

            $order = Order::with('items.ingredients')
                ->lockForUpdate()
                ->findOrFail($orderId);

            if ($order->status !== 'baru') {
                throw ValidationException::withMessages([
                    'status' => 'Pesanan hanya dapat dibatalkan saat status Baru.',
                ]);
            }

            $returns = [];

            foreach ($order->items as $item) {
                foreach ($item->ingredients as $used) {
                    $id = $used->ingredient_id;

                    $quantity = (int) round(
                        (float) $used->quantity_used * 1000
                    );

                    $returns[$id] = ($returns[$id] ?? 0) + $quantity;
                }
            }

            $ingredients = Ingredient::whereIn('id', array_keys($returns))
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($returns as $ingredientId => $quantity) {
                $ingredient = $ingredients->get($ingredientId);

                if (!$ingredient) {
                    continue;
                }

                $before = (int) round(
                    (float) $ingredient->current_stock * 1000
                );

                $after = $before + $quantity;

                $ingredient->current_stock = number_format(
                    $after / 1000,
                    3,
                    '.',
                    ''
                );

                $ingredient->save();

                StockMovement::create([
                    'ingredient_id' => $ingredient->id,
                    'user_id' => $actor->id,
                    'order_id' => $order->id,
                    'movement_type' => 'pengembalian',
                    'quantity' => number_format($quantity / 1000, 3, '.', ''),
                    'stock_before' => number_format($before / 1000, 3, '.', ''),
                    'stock_after' => number_format($after / 1000, 3, '.', ''),
                    'notes' => 'Pengembalian stok pesanan '
                        . $order->order_code,
                ]);
            }

            $order->status = 'dibatalkan';
            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $actor->id,
                'old_status' => 'baru',
                'new_status' => 'dibatalkan',
                'notes' => $notes ?? 'Pesanan dibatalkan oleh Kasir.',
            ]);

            return $order;
        });
    }
}
