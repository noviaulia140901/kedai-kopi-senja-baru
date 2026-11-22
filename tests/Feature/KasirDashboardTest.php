<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_dashboard_displays_order_summary_and_latest_orders(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->freezeTime();

        foreach (['baru', 'baru', 'diproses', 'selesai'] as $index => $status) {
            Order::create([
                'order_code' => sprintf('KS-DASH-%03d', $index + 1),
                'cashier_id' => $cashier->id,
                'order_channel' => 'kasir',
                'status' => $status,
                'subtotal' => 10000,
                'service_charge' => 500,
                'tax' => 1050,
                'total' => 11550,
                'ordered_at' => now()->addMinutes($index),
            ]);
        }

        $response = $this
            ->actingAs($cashier)
            ->get(route('kasir.dashboard'));

        $response
            ->assertOk()
            ->assertViewIs('kasir.dashboard')
            ->assertViewHasAll([
                'totalOrders' => 4,
                'newOrders' => 2,
                'processingOrders' => 1,
                'completedOrders' => 1,
            ])
            ->assertSee('KS-DASH-004')
            ->assertSee('Buat Pesanan')
            ->assertSee('Daftar Pesanan');
    }

    public function test_kasir_dashboard_displays_empty_state_when_no_orders_exist(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $response = $this
            ->actingAs($cashier)
            ->get(route('kasir.dashboard'));

        $response
            ->assertOk()
            ->assertViewIs('kasir.dashboard')
            ->assertViewHasAll([
                'totalOrders' => 0,
                'newOrders' => 0,
                'processingOrders' => 0,
                'completedOrders' => 0,
            ])
            ->assertSee('Belum ada pesanan terbaru.')
            ->assertSee('Buat Pesanan')
            ->assertSee('Daftar Pesanan');
    }

    public function test_guest_is_redirected_to_login_from_kasir_dashboard(): void
    {
        $this->get(route('kasir.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_manager_cannot_open_kasir_dashboard(): void
    {
        $manager = User::factory()->create(['role' => 'manajer']);

        $this->actingAs($manager)
            ->get(route('kasir.dashboard'))
            ->assertForbidden();
    }
}
