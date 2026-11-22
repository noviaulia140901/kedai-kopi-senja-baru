<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTables extends Migration
{
    public function up(): void
    {
        // Tabel pesanan
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 30)->unique();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('cashier_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->enum('order_channel', ['apk', 'kasir']);
            $table->unsignedTinyInteger('table_number')->nullable();

            $table->enum('status', [
                'baru',
                'diproses',
                'selesai',
                'dibatalkan',
            ])->default('baru');

            $table->enum('payment_method', [
                'tunai',
                'qris',
            ])->nullable();

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('service_charge', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->text('notes')->nullable();
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamps();

            $table->index(['status', 'ordered_at']);
            $table->index('order_channel');
        });

        // Detail menu dalam pesanan
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('menu_id')
                ->constrained('menus')
                ->restrictOnDelete();

            $table->string('menu_name', 150);
            $table->decimal('unit_price', 15, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });

        // Bahan yang digunakan untuk setiap item pesanan
        Schema::create('order_item_ingredients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_item_id')
                ->constrained('order_items')
                ->cascadeOnDelete();

            $table->foreignId('ingredient_id')
                ->constrained('ingredients')
                ->restrictOnDelete();

            $table->string('ingredient_name', 150);
            $table->decimal('quantity_used', 15, 3);
            $table->decimal('unit_cost', 15, 2);
            $table->decimal('total_cost', 15, 2);

            $table->timestamps();
        });

        // Riwayat perubahan status pesanan
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('old_status', 30)->nullable();
            $table->string('new_status', 30);
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->index('new_status');
        });

        // Riwayat perubahan stok bahan
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ingredient_id')
                ->constrained('ingredients')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->restrictOnDelete();

            $table->foreignId('stock_entry_id')
                ->nullable()
                ->constrained('stock_entries')
                ->restrictOnDelete();

            $table->enum('movement_type', [
                'stok_masuk',
                'pemakaian',
                'pengembalian',
                'penyesuaian',
            ]);

            $table->decimal('quantity', 15, 3);
            $table->decimal('stock_before', 15, 3);
            $table->decimal('stock_after', 15, 3);

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->index(['ingredient_id', 'movement_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_item_ingredients');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
}
