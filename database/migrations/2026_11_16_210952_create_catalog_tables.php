<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogTables extends Migration
{
    public function up(): void
    {
        // Tabel daftar menu
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('selling_price', 15, 2);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
}
