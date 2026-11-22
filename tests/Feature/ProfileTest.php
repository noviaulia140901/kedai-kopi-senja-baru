<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_manager_profile_page_uses_manager_layout_and_keeps_password_form(): void
    {
        $manager = User::factory()->create(['role' => 'manajer']);

        $response = $this
            ->actingAs($manager)
            ->get(route('profile.edit'));

        $response
            ->assertOk()
            ->assertSee(route('manajer.dashboard'), false)
            ->assertSee(route('manajer.ingredients.index'), false)
            ->assertSee(route('manajer.categories.index'), false)
            ->assertSee(route('manajer.menus.index'), false)
            ->assertSee(route('manajer.recipes.index'), false)
            ->assertSee(route('manajer.stock-entries.index'), false)
            ->assertSee(route('manajer.supplier-prices.index'), false)
            ->assertSee(route('manajer.costs.index'), false)
            ->assertSee(route('manajer.reports.index'), false)
            ->assertSee(route('password.update'), false)
            ->assertSee('update_password_current_password')
            ->assertSee('Ganti Password');
    }

    public function test_cashier_password_page_uses_cashier_layout_and_keeps_navigation(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $response = $this
            ->actingAs($cashier)
            ->get(route('profile.edit'));

        $response
            ->assertOk()
            ->assertSee(route('kasir.dashboard'), false)
            ->assertSee(route('kasir.menus.index'), false)
            ->assertSee(route('kasir.orders.index'), false)
            ->assertSee(route('kasir.customers.reset-password'), false)
            ->assertSee(route('profile.edit'), false)
            ->assertSee('Ganti Password')
            ->assertDontSee('Profil')
            ->assertSee(route('password.update'), false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="_method" value="put"', false)
            ->assertSee('update_password_current_password')
            ->assertDontSee(route('manajer.dashboard'), false);
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_user_cannot_delete_account_through_profile(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response->assertMethodNotAllowed();

        $this->assertNotNull($user->fresh());
    }
}
