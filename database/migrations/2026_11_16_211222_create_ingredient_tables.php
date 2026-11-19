<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientTables extends Migration
{
    public function up(): void
    {
        // Tabel bahan baku
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('base_unit', 10);
            $table->decimal('current_stock', 15, 3)->default(0);
            $table->decimal('minimum_stock', 15, 3)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel resep menu
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_id')
                ->constrained('menus')
                ->cascadeOnDelete();

            $table->foreignId('ingredient_id')
                ->constrained('ingredients')
                ->restrictOnDelete();

            $table->decimal('quantity', 15, 3);
            $table->timestamps();

            $table->unique(['menu_id', 'ingredient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('ingredients');
    }
}
