<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category',
        'sku',
        'price',
        'quantity',
        'min_quantity',
        'unit',
        'supplier',
        'purchase_date',
        'expiry_date',
        'location',
        'notes',
        'is_active',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'min_quantity' => 'integer',
        'purchase_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Accessor for low stock warning
    public function getIsLowStockAttribute()
    {
        return $this->quantity <= $this->min_quantity;
    }

    // Scope for active items
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for low stock items
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= min_quantity');
    }

    // Scope for expired items
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    // Scope for expiring soon (within 30 days)
    public function scopeExpiringSoon($query)
    {
        return $query->where('expiry_date', '<=', now()->addDays(30))
                    ->where('expiry_date', '>', now());
    }

    // Relationships
    public function stockMovements(): HasMany
    {
        return $this->hasMany(InventoryStockMovement::class);
    }
}
