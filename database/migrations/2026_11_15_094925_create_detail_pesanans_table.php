<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pesanan_id')
                    ->constrained('pesanans')
                    ->cascadeOnDelete();

            $table->foreignId('menu_id')
                    ->constrained('menus')
                    ->restrictOnDelete();

            $table->unsignedInteger('jumlah_porsi');

            $table->decimal('harga_jual_saat_pesan', 12, 2);

            $table->decimal('subtotal_item',12, 2 );

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
