<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'supplier_id',
        'capital_price',
        'sell_price',
        'quantity_kilo',
        'image_path',
        'category',
    ];

    protected $casts = [
        'capital_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'quantity_kilo' => 'float',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
