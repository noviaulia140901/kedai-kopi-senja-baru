<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_recipe_landing_page_lists_menus_without_showing_all_recipe_rows(): void
    {
        $manager = User::factory()->create(['role' => 'manajer']);
        $americano = $this->createMenu('Americano');
        $latte = $this->createMenu('Latte');
        $coffeeBeans = $this->createIngredient('Biji Kopi Americano');
        $milk = $this->createIngredient('Susu Latte');

        Recipe::create([
            'menu_id' => $americano->id,
            'ingredient_id' => $coffeeBeans->id,
            'quantity' => 18,
        ]);
        Recipe::create([
            'menu_id' => $latte->id,
            'ingredient_id' => $milk->id,
            'quantity' => 100,
        ]);

        $response = $this->actingAs($manager)->get(route('manajer.recipes.index'));

        $response
            ->assertOk()
            ->assertSee('Americano')
            ->assertSee('Latte')
            ->assertSee(route('manajer.recipes.index', ['menu' => $americano->id]), false)
            ->assertDontSee('Biji Kopi Americano')
            ->assertDontSee('Susu Latte');
    }

    public function test_selecting_a_menu_shows_only_its_recipe_and_a_back_link(): void
    {
        $manager = User::factory()->create(['role' => 'manajer']);
        $americano = $this->createMenu('Americano');
        $latte = $this->createMenu('Latte');
        $coffeeBeans = $this->createIngredient('Biji Kopi Americano');
        $milk = $this->createIngredient('Susu Latte');
        $water = $this->createIngredient('Air', 'ml');
        $cup = $this->createIngredient('Gelas', 'pcs');
        $syrup = $this->createIngredient('Sirup', 'ml');
        $cocoa = $this->createIngredient('Bubuk Kakao');

        Recipe::create([
            'menu_id' => $americano->id,
            'ingredient_id' => $coffeeBeans->id,
            'quantity' => 18,
        ]);
        Recipe::create([
            'menu_id' => $americano->id,
            'ingredient_id' => $water->id,
            'quantity' => 120,
        ]);
        Recipe::create([
            'menu_id' => $americano->id,
            'ingredient_id' => $cup->id,
            'quantity' => 1,
        ]);
        Recipe::create([
            'menu_id' => $americano->id,
            'ingredient_id' => $syrup->id,
            'quantity' => 1.5,
        ]);
        Recipe::create([
            'menu_id' => $americano->id,
            'ingredient_id' => $cocoa->id,
            'quantity' => 1.234,
        ]);
        Recipe::create([
            'menu_id' => $latte->id,
            'ingredient_id' => $milk->id,
            'quantity' => 100,
        ]);

        $response = $this->actingAs($manager)
            ->get(route('manajer.recipes.index', ['menu' => $americano->id]));

        $response
            ->assertOk()
            ->assertSee('Americano')
            ->assertSee('Biji Kopi Americano')
            ->assertSee('18')
            ->assertSee('120')
            ->assertSee('1')
            ->assertSee('pcs')
            ->assertSee('1,5')
            ->assertSee('1,234')
            ->assertDontSee('18.000 g')
            ->assertSee(route('manajer.recipes.index'), false)
            ->assertSee('Tambah Bahan Resep')
            ->assertViewHas('selectedMenu', function (Menu $selectedMenu) use ($coffeeBeans): bool {
                return $selectedMenu->name === 'Americano'
                    && $selectedMenu->recipes->count() === 5
                    && $selectedMenu->recipes->contains('ingredient_id', $coffeeBeans->id);
            });
    }

    public function test_manager_can_add_a_recipe_ingredient_and_return_to_that_menu(): void
    {
        $manager = User::factory()->create(['role' => 'manajer']);
        $menu = $this->createMenu('Americano');
        $ingredient = $this->createIngredient('Biji Kopi');

        $response = $this->actingAs($manager)
            ->post(route('manajer.recipes.store'), [
                'menu_id' => $menu->id,
                'ingredient_id' => $ingredient->id,
                'quantity' => '18.500',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('manajer.recipes.index', ['menu' => $menu->id]));

        $this->assertDatabaseHas('recipes', [
            'menu_id' => $menu->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 18.5,
        ]);
    }

    public function test_manager_can_edit_a_recipe_ingredient_and_quantity(): void
    {
        $manager = User::factory()->create(['role' => 'manajer']);
        $menu = $this->createMenu('Americano');
        $coffeeBeans = $this->createIngredient('Biji Kopi');
        $water = $this->createIngredient('Air');
        $recipe = Recipe::create([
            'menu_id' => $menu->id,
            'ingredient_id' => $coffeeBeans->id,
            'quantity' => 18,
        ]);

        $response = $this->actingAs($manager)
            ->put(route('manajer.recipes.update', $recipe), [
                'menu_id' => $menu->id,
                'ingredient_id' => $water->id,
                'quantity' => '150.000',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('manajer.recipes.index', ['menu' => $menu->id]));

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'menu_id' => $menu->id,
            'ingredient_id' => $water->id,
            'quantity' => 150,
        ]);
        $this->assertDatabaseMissing('recipes', [
            'id' => $recipe->id,
            'ingredient_id' => $coffeeBeans->id,
        ]);
    }

    public function test_manager_can_delete_a_recipe_and_return_to_that_menu(): void
    {
        $manager = User::factory()->create(['role' => 'manajer']);
        $menu = $this->createMenu('Americano');
        $ingredient = $this->createIngredient('Biji Kopi');
        $recipe = Recipe::create([
            'menu_id' => $menu->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 18,
        ]);

        $response = $this->actingAs($manager)
            ->delete(route('manajer.recipes.destroy', $recipe));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('manajer.recipes.index', ['menu' => $menu->id]));

        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    public function test_cashier_cannot_access_recipe_management(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($cashier)
            ->get(route('manajer.recipes.index'))
            ->assertForbidden();
    }

    private function createMenu(string $name): Menu
    {
        $category = Category::create([
            'name' => "{$name} Category",
            'is_active' => true,
        ]);

        return Menu::create([
            'category_id' => $category->id,
            'name' => $name,
            'selling_price' => 10000,
            'is_active' => true,
        ]);
    }

    private function createIngredient(string $name, string $unit = 'g'): Ingredient
    {
        return Ingredient::create([
            'name' => $name,
            'base_unit' => $unit,
            'current_stock' => 1000,
            'minimum_stock' => 0,
            'is_active' => true,
        ]);
    }
}
