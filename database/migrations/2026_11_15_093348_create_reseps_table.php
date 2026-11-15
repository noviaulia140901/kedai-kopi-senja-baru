<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reseps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_id')
                    ->constrained('menus')
                    ->cascadeOnDelete();

            $table->foreignId('bahan_id')
                    ->constrained('bahans')
                    ->restrictOnDelete();

            $table->decimal('takaran', 12, 3);

            $table->timestamps();

            $table->unique(['menu_id', 'bahan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
