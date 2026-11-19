<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTables extends Migration
{
    public function up(): void
    {
        // Catatan stok bahan masuk
        Schema::create('stock_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ingredient_id')
                ->constrained('ingredients')
                ->restrictOnDelete();

            $table->foreignId('entered_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('quantity', 15, 3);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);

            $table->string('supplier_name', 150)->nullable();
            $table->date('entry_date');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->index('entry_date');
        });

        // Riwayat harga bahan dari supplier
        Schema::create('ingredient_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ingredient_id')
                ->constrained('ingredients')
                ->cascadeOnDelete();

            $table->string('supplier_code', 100)->nullable();
            $table->decimal('price_per_unit', 15, 2);
            $table->timestamp('supplier_updated_at')->nullable();
            $table->timestamp('fetched_at')->nullable();

            $table->timestamps();
            $table->index('supplier_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_prices');
        Schema::dropIfExists('stock_entries');
    }
}
