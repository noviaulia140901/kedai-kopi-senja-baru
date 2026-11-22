<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class KasirOrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_can_filter_orders_and_view_items_with_paginated_results(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $category = Category::create(['name' => 'Kopi']);
        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Kopi Susu',
            'selling_price' => 25000,
            'is_active' => true,
        ]);

        for ($index = 1; $index <= 11; $index++) {
            $order = $this->createOrder(
                $cashier,
                'baru',
                sprintf('KS-LIST-%03d', $index),
                $index
            );

            if ($index === 11) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'unit_price' => 25000,
                    'quantity' => 2,
                    'subtotal' => 50000,
                ]);
            }
        }

        $this->createOrder($cashier, 'diproses', 'KS-LIST-PROCESSING', 12);
        $this->createOrder($cashier, 'baru', 'KS-LIST-APK', 13, 'apk');

        $response = $this->actingAs($cashier)
            ->get(route('kasir.orders.index', ['status' => 'baru']));

        $response
            ->assertOk()
            ->assertViewIs('kasir.orders.index')
            ->assertViewHas(
                'orders',
                fn (LengthAwarePaginator $orders): bool => $orders->total() === 11
            )
            ->assertSee('Daftar Pesanan Masuk')
            ->assertSee('Waktu')
            ->assertSee('Sumber')
            ->assertSee('Item')
            ->assertSee('Kasir')
            ->assertSee('Kopi Susu x2')
            ->assertSee('Proses')
            ->assertSee('Batalkan')
            ->assertSee('Struk')
            ->assertSee('KS-LIST-011')
            ->assertDontSee('KS-LIST-PROCESSING')
            ->assertDontSee('KS-LIST-APK')
            ->assertSee('status=baru', false)
            ->assertSee('aria-current="page"', false);
    }

    public function test_kasir_can_advance_order_status_in_sequence(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $order = $this->createOrder($cashier, 'baru', 'KS-STATUS-001');

        $this->actingAs($cashier)
            ->patch(route('kasir.orders.status', $order->id), ['status' => 'diproses'])
            ->assertRedirect(route('kasir.orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'diproses',
        ]);
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'old_status' => 'baru',
            'new_status' => 'diproses',
        ]);

        $this->patch(route('kasir.orders.status', $order->id), ['status' => 'selesai'])
            ->assertRedirect(route('kasir.orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'selesai',
        ]);
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'old_status' => 'diproses',
            'new_status' => 'selesai',
        ]);
    }

    public function test_kasir_cannot_skip_status_or_cancel_an_order_after_processing(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $newOrder = $this->createOrder($cashier, 'baru', 'KS-INVALID-001');
        $processingOrder = $this->createOrder($cashier, 'diproses', 'KS-INVALID-002');

        $this->actingAs($cashier)
            ->patch(route('kasir.orders.status', $newOrder->id), ['status' => 'selesai'])
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('orders', [
            'id' => $newOrder->id,
            'status' => 'baru',
        ]);
        $this->assertDatabaseMissing('order_status_histories', [
            'order_id' => $newOrder->id,
        ]);

        $this->patch(route('kasir.orders.cancel', $processingOrder->id))
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('orders', [
            'id' => $processingOrder->id,
            'status' => 'diproses',
        ]);
        $this->assertDatabaseMissing('order_status_histories', [
            'order_id' => $processingOrder->id,
        ]);
    }

    public function test_kasir_can_cancel_a_new_order_and_open_its_receipt(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $order = $this->createOrder($cashier, 'baru', 'KS-CANCEL-001');

        $this->actingAs($cashier)
            ->get(route('kasir.orders.receipt', $order->id))
            ->assertOk()
            ->assertViewIs('kasir.orders.receipt')
            ->assertSee('KS-CANCEL-001');

        $this->patch(route('kasir.orders.cancel', $order->id))
            ->assertRedirect(route('kasir.orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'dibatalkan',
        ]);
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'old_status' => 'baru',
            'new_status' => 'dibatalkan',
        ]);
    }

    public function test_receipt_uses_snapshot_prices_and_saved_transaction_totals(): void
    {
        $this->travelTo('2026-11-20 10:42:00');

        $cashier = User::factory()->create(['role' => 'kasir']);
        $category = Category::create(['name' => 'Kopi']);
        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Kopi Susu',
            'selling_price' => 25000,
            'is_active' => true,
        ]);
        $order = $this->createOrder($cashier, 'baru', 'KS-SNAPSHOT-001');
        $order->update([
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'service_charge' => 2500,
            'tax' => 5250,
            'total' => 57750,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'menu_name' => 'Kopi Susu',
            'unit_price' => 25000,
            'quantity' => 2,
            'subtotal' => 50000,
        ]);
        $menu->update(['selling_price' => 40000]);

        $response = $this->actingAs($cashier)
            ->get(route('kasir.orders.receipt', $order->id));

        $response
            ->assertOk()
            ->assertViewIs('kasir.orders.receipt')
            ->assertSee('KS-SNAPSHOT-001')
            ->assertSee('20/11/2026 10:42')
            ->assertSee('Kopi Susu')
            ->assertSee('QRIS')
            ->assertSee('Rp 25.000')
            ->assertSee('Rp 50.000')
            ->assertSee('Rp 2.500')
            ->assertSee('Rp 5.250')
            ->assertSee('Rp 57.750')
            ->assertDontSee('Rp 40.000');
    }

    public function test_guest_and_manager_cannot_access_cashier_orders_or_receipts(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $order = $this->createOrder($cashier, 'baru', 'KS-ACCESS-001');
        $apkOrder = $this->createOrder($cashier, 'baru', 'KS-ACCESS-APK', 0, 'apk');

        $this->get(route('kasir.orders.index'))
            ->assertRedirect(route('login'));
        $this->get(route('kasir.orders.receipt', $order->id))
            ->assertRedirect(route('login'));

        $manager = User::factory()->create(['role' => 'manajer']);
        $this->actingAs($manager)
            ->get(route('kasir.orders.index'))
            ->assertForbidden();
        $this->get(route('kasir.orders.receipt', $order->id))
            ->assertForbidden();

        $this->actingAs($cashier)
            ->get(route('kasir.orders.receipt', $apkOrder->id))
            ->assertNotFound();
    }

    private function createOrder(
        User $cashier,
        string $status,
        string $orderCode,
        int $minutesAfterNow = 0,
        string $orderChannel = 'kasir'
    ): Order {
        return Order::create([
            'order_code' => $orderCode,
            'cashier_id' => $orderChannel === 'kasir' ? $cashier->id : null,
            'order_channel' => $orderChannel,
            'status' => $status,
            'payment_method' => 'tunai',
            'subtotal' => 10000,
            'service_charge' => 500,
            'tax' => 1050,
            'total' => 11550,
            'ordered_at' => now()->addMinutes($minutesAfterNow),
        ]);
    }
}
