<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngredientPrice extends Model
{
    protected $fillable = [
        'ingredient_id',
        'supplier_code',
        'price_per_unit',
        'supplier_updated_at',
        'fetched_at',
    ];

    protected function casts(): array
    {
        return [
            'price_per_unit' => 'decimal:2',
            'supplier_updated_at' => 'datetime',
            'fetched_at' => 'datetime',
        ];
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
