<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class InventoryStockMovement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'inventory_item_id',
        'movement_type',
        'quantity',
        'previous_quantity',
        'new_quantity',
        'reference_type',
        'reference_id',
        'reference_number',
        'notes',
        'location_from',
        'location_to',
        'unit_cost',
        'total_cost',
        'performed_by',
        'movement_date',
        'is_active'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'movement_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    // Scopes
    public function scopeIn($query)
    {
        return $query->where('movement_type', 'in');
    }

    public function scopeOut($query)
    {
        return $query->where('movement_type', 'out');
    }

    public function scopeAdjustment($query)
    {
        return $query->where('movement_type', 'adjustment');
    }

    public function scopeTransfer($query)
    {
        return $query->where('movement_type', 'transfer');
    }

    public function scopeReturn($query)
    {
        return $query->where('movement_type', 'return');
    }

    public function scopeDamage($query)
    {
        return $query->where('movement_type', 'damage');
    }

    public function scopeLoss($query)
    {
        return $query->where('movement_type', 'loss');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getFormattedMovementTypeAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->movement_type));
    }

    public function getQuantityChangeAttribute()
    {
        return $this->movement_type === 'in' || $this->movement_type === 'return' 
            ? '+' . $this->quantity 
            : '-' . $this->quantity;
    }

    public function getIsPositiveAttribute()
    {
        return in_array($this->movement_type, ['in', 'return']);
    }

    public function getIsNegativeAttribute()
    {
        return in_array($this->movement_type, ['out', 'damage', 'loss']);
    }

    // Constants
    const MOVEMENT_TYPES = [
        'in' => 'Stock In',
        'out' => 'Stock Out',
        'adjustment' => 'Stock Adjustment',
        'transfer' => 'Transfer',
        'return' => 'Return',
        'damage' => 'Damage',
        'loss' => 'Loss'
    ];

    const REFERENCE_TYPES = [
        'purchase' => 'Purchase',
        'sale' => 'Sale',
        'adjustment' => 'Adjustment',
        'transfer' => 'Transfer',
        'return' => 'Return',
        'damage' => 'Damage',
        'loss' => 'Loss',
        'manual' => 'Manual Entry'
    ];

    // Static methods
    public static function createMovement($inventoryItemId, $movementType, $quantity, $options = [])
    {
        $item = InventoryItem::findOrFail($inventoryItemId);
        $previousQuantity = $item->quantity;
        
        // Calculate new quantity based on movement type
        if (in_array($movementType, ['in', 'return'])) {
            $newQuantity = $previousQuantity + $quantity;
        } else {
            $newQuantity = max(0, $previousQuantity - $quantity);
        }

        // Create movement record
        $movement = self::create(array_merge([
            'inventory_item_id' => $inventoryItemId,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $newQuantity,
            'movement_date' => $options['movement_date'] ?? now()->toDateString(),
            'performed_by' => $options['performed_by'] ?? auth()->user()->name ?? 'System',
            'notes' => $options['notes'] ?? null,
            'reference_type' => $options['reference_type'] ?? null,
            'reference_id' => $options['reference_id'] ?? null,
            'reference_number' => $options['reference_number'] ?? null,
            'location_from' => $options['location_from'] ?? null,
            'location_to' => $options['location_to'] ?? null,
            'unit_cost' => $options['unit_cost'] ?? null,
            'total_cost' => $options['total_cost'] ?? null,
        ], $options));

        // Update inventory item quantity
        $item->update(['quantity' => $newQuantity]);

        return $movement;
    }
}
