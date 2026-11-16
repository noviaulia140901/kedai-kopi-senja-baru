<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
