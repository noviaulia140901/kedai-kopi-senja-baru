<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Services\SupplierPriceService;

class SupplierPriceController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::with([
            'prices' => function ($query) {
                $query->latest('fetched_at');
            },
        ])
            ->orderBy('name')
            ->paginate(10);

        return view(
            'manajer.supplier-prices.index',
            compact('ingredients')
        );
    }

    public function sync(SupplierPriceService $supplierPriceService)
    {
        $result = $supplierPriceService->sync();

        if ($result['success']) {
            return redirect()
                ->route('manajer.supplier-prices.index')
                ->with('success', $result['message']);
        }

        return redirect()
            ->route('manajer.supplier-prices.index')
            ->with('error', $result['message']);
    }
}
