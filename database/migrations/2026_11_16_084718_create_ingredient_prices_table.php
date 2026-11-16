<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }
};
