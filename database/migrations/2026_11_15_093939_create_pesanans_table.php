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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                    ->constrained('users')
                    ->restrictOnDelete();

            $table->string('nomor_pesanan')->unique();

            $table->unsignedTinyInteger('nomor_meja')->nullable();

            $table->enum('metode_bayar', ['Tunai', 'QRIS']);

            $table->enum('status', [
                'Baru',
                'Diproses',
                'Selesai',
                'Dibatalkan',
            ])->default('Baru');

            $table->dateTime('tanggal_pesanan');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('service_charge', 12, 2)->default(0);
            $table->decimal('pajak', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
