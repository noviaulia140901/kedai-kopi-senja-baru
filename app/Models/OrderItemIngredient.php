<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemIngredient extends Model
{
    protected $fillable = [
        'order_item_id', 'ingredient_id', 'ingredient_name',
        'quantity_used', 'unit_cost', 'total_cost',
    ];

    protected function casts(): array
    {
        return [
            'quantity_used' => 'decimal:3',
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
