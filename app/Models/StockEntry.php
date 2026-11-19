<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockEntry extends Model
{
    protected $fillable = [
        'ingredient_id',
        'entered_by',
        'quantity',
        'unit_price',
        'total_price',
        'supplier_name',
        'entry_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'entry_date' => 'date',
        ];
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }
}
