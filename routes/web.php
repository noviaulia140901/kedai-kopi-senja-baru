<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManajerDashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\CustomerPasswordResetController;
use App\Http\Controllers\KasirMenuController;
use App\Http\Controllers\SupplierPriceController;
use App\Http\Controllers\ManajerCostController;
use App\Http\Controllers\ManajerReportController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Fitur Kasir
Route::middleware(['auth', 'role:kasir'])->group(function () {

    Route::get('/dashboard/kasir', function () {
        return view('dashboard');
    })->name('kasir.dashboard');

    // Reset password pelanggan
    Route::get('/kasir/reset-password', [CustomerPasswordResetController::class, 'index'])
        ->name('kasir.customers.reset-password');

    Route::post('/kasir/reset-password', [CustomerPasswordResetController::class, 'store'])
        ->name('kasir.customers.reset-password.store');

    Route::get('/kasir/menu', [KasirMenuController::class, 'index'])
        ->name('kasir.menus.index');

    Route::post('/kasir/pesanan', [KasirMenuController::class, 'store'])
        ->name('kasir.orders.store');
    Route::patch('/kasir/pesanan/{order}/status', [KasirMenuController::class, 'updateStatus'])
        ->name('kasir.orders.status');

    Route::patch('/kasir/pesanan/{order}/batal', [KasirMenuController::class, 'cancel'])
        ->name('kasir.orders.cancel');
});

// Fitur Manajer
Route::middleware(['auth', 'role:manajer'])->group(function () {

    // Dashboard
    Route::get('/dashboard/manajer', [ManajerDashboardController::class, 'index'])
        ->name('manajer.dashboard');

    // Kategori
    Route::get('/manajer/kategori', [CategoryController::class, 'index'])
        ->name('manajer.categories.index');

    Route::post('/manajer/kategori', [CategoryController::class, 'store'])
        ->name('manajer.categories.store');

    Route::put('/manajer/kategori/{category}', [CategoryController::class, 'update'])
        ->name('manajer.categories.update');

    Route::delete('/manajer/kategori/{category}', [CategoryController::class, 'destroy'])
        ->name('manajer.categories.destroy');

    // Menu
    Route::get('/manajer/menu', [MenuController::class, 'index'])
        ->name('manajer.menus.index');

    Route::post('/manajer/menu', [MenuController::class, 'store'])
        ->name('manajer.menus.store');

    Route::put('/manajer/menu/{menu}', [MenuController::class, 'update'])
        ->name('manajer.menus.update');

    Route::delete('/manajer/menu/{menu}', [MenuController::class, 'destroy'])
        ->name('manajer.menus.destroy');

    // Bahan Baku
    Route::get('/manajer/bahan-baku', [IngredientController::class, 'index'])
        ->name('manajer.ingredients.index');

    Route::post('/manajer/bahan-baku', [IngredientController::class, 'store'])
        ->name('manajer.ingredients.store');

    Route::put('/manajer/bahan-baku/{ingredient}', [IngredientController::class, 'update'])
        ->name('manajer.ingredients.update');

    Route::delete('/manajer/bahan-baku/{ingredient}', [IngredientController::class, 'destroy'])
        ->name('manajer.ingredients.destroy');

    // Resep
    Route::get('/manajer/resep', [RecipeController::class, 'index'])
        ->name('manajer.recipes.index');

    Route::post('/manajer/resep', [RecipeController::class, 'store'])
        ->name('manajer.recipes.store');

    Route::put('/manajer/resep/{recipe}', [RecipeController::class, 'update'])
        ->name('manajer.recipes.update');

    Route::delete('/manajer/resep/{recipe}', [RecipeController::class, 'destroy'])
        ->name('manajer.recipes.destroy');

    // Stok Masuk
    Route::get('/manajer/stok-masuk', [StockEntryController::class, 'index'])
        ->name('manajer.stock-entries.index');

    Route::post('/manajer/stok-masuk', [StockEntryController::class, 'store'])
        ->name('manajer.stock-entries.store');

    // Harga Supplier
    Route::get('/manajer/harga-supplier',
    [SupplierPriceController::class, 'index'])
        ->name('manajer.supplier-prices.index');

    Route::post('/manajer/harga-supplier/sinkronisasi',
    [SupplierPriceController::class, 'sync'])
        ->name('manajer.supplier-prices.sync');

    // HPP dan Margin
    Route::get('/manajer/hpp-margin', [ManajerCostController::class, 'index'])
        ->name('manajer.costs.index');

    // Laporan
    Route::get('/manajer/laporan', [ManajerReportController::class, 'index'])
        ->name('manajer.reports.index');

});

// Profil
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});

// Autentikasi
require __DIR__.'/auth.php';
