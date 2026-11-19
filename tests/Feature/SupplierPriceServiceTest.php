<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\IngredientPrice;
use App\Services\SupplierPriceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SupplierPriceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.supplier.base_url' => 'https://supplier.test/kafe',
            'services.supplier.api_key' => 'test-key',
        ]);
    }

    public function test_supplier_prices_are_updated_when_api_succeeds(): void
    {
        $ingredient = Ingredient::create([
            'name' => 'Biji kopi arabika',
            'base_unit' => 'g',
            'current_stock' => 1000,
            'minimum_stock' => 100,
            'is_active' => true,
        ]);

        $price = IngredientPrice::create([
            'ingredient_id' => $ingredient->id,
            'supplier_code' => 'BHN-001',
            'price_per_unit' => 150,
        ]);

        Http::fake([
            '*' => Http::response([
                'diperbarui' => '2026-11-01',
                'data' => [
                    [
                        'kode' => 'BHN-001',
                        'nama' => 'Biji kopi arabika',
                        'satuan_dasar' => 'g',
                        'harga_per_satuan' => 185.4,
                    ],
                ],
            ], 200),
        ]);

        $result = app(SupplierPriceService::class)->sync();

        $this->assertTrue($result['success']);
        $this->assertSame(1, $result['updated']);

        $this->assertEquals(
            '185.40',
            $price->fresh()->price_per_unit
        );

        $this->assertNotNull($price->fresh()->fetched_at);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://supplier.test/kafe/harga-bahan'
                && $request->hasHeader('X-API-Key', 'test-key');
        });
    }

    public function test_old_supplier_price_is_kept_when_api_fails(): void
    {
        $ingredient = Ingredient::create([
            'name' => 'Susu UHT',
            'base_unit' => 'ml',
            'current_stock' => 1000,
            'minimum_stock' => 100,
            'is_active' => true,
        ]);

        $price = IngredientPrice::create([
            'ingredient_id' => $ingredient->id,
            'supplier_code' => 'BHN-003',
            'price_per_unit' => 18.54,
        ]);

        Http::fake([
            '*' => Http::response(['message' => 'Unauthorized'], 401),
        ]);

        $result = app(SupplierPriceService::class)->sync();

        $this->assertFalse($result['success']);
        $this->assertSame(0, $result['updated']);

        $this->assertEquals(
            '18.54',
            $price->fresh()->price_per_unit
        );
    }
}
