<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'name', 'category', 'unit', 'quantity_in_stock',
        'low_stock_threshold', 'unit_cost', 'unit_price',
    ];

    public function jobCardParts()
    {
        return $this->hasMany(JobCardPart::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity_in_stock <= $this->low_stock_threshold;
    }

    /**
     * Deduct stock when a part is used on a job card. Throws if insufficient stock.
     */
    public function deductStock(int $quantity): void
    {
        if ($quantity > $this->quantity_in_stock) {
            throw new \RuntimeException("Insufficient stock for {$this->name}. Available: {$this->quantity_in_stock}, requested: {$quantity}.");
        }

        $this->decrement('quantity_in_stock', $quantity);
    }
}
