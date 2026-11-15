<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('satuan_dasar', 10);
            $table->decimal('stok', 12, 3)->default(0);
            $table->decimal('batas_minimum', 12, 3)->default(0);
            $table->decimal('harga_per_satuan_dasar', 12, 2)->default(0);
            $table->dateTime('tanggal_pembaruan_harga')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('bahans');
    }
};
