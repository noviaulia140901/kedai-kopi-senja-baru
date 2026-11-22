<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirMenuOrderPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_page_shows_available_portions_and_escapes_menu_name(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $category = Category::create(['name' => 'Kopi']);
        $ingredient = Ingredient::create([
            'name' => 'Biji Kopi',
            'base_unit' => 'kg',
            'current_stock' => 1,
            'minimum_stock' => 0,
            'is_active' => true,
        ]);
        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Kopi <script>alert(1)</script>',
            'selling_price' => 25000,
            'is_active' => true,
        ]);
        Recipe::create([
            'menu_id' => $menu->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 0.250,
        ]);

        $response = $this->actingAs($cashier)->get(route('kasir.menus.index'));

        $response
            ->assertOk()
            ->assertSee('data-max-stock="4"', false)
            ->assertSee('data-category-filter="kopi"', false)
            ->assertSee('method="POST"', false)
            ->assertSee('action="'.route('kasir.orders.store').'"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="items['.$menu->id.'][menu_id]"', false)
            ->assertSee('name="items['.$menu->id.'][quantity]"', false)
            ->assertSee('name="notes"', false)
            ->assertSee('value="tunai"', false)
            ->assertSee('value="qris"', false)
            ->assertSee('Simpan Pesanan')
            ->assertSee('Kosongkan')
            ->assertSee('Kopi <script>alert(1)</script>')
            ->assertDontSee('Kopi <script>alert(1)</script>', false);
    }
}
