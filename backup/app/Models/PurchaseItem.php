<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purchase_id',
        'inventory_item_id',
        'item_name',
        'item_sku',
        'description',
        'quantity_ordered',
        'quantity_received',
        'quantity_pending',
        'unit_cost',
        'total_cost',
        'discount_percentage',
        'discount_amount',
        'tax_percentage',
        'tax_amount',
        'unit',
        'expiry_date',
        'notes',
        'is_received',
        'received_date'
    ];

    protected $casts = [
        'quantity_ordered' => 'integer',
        'quantity_received' => 'integer',
        'quantity_pending' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'expiry_date' => 'date',
        'received_date' => 'date',
        'is_received' => 'boolean'
    ];

    // Relationships
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    // Accessors
    public function getFormattedUnitCostAttribute()
    {
        return '₹' . number_format($this->unit_cost, 2);
    }

    public function getFormattedTotalCostAttribute()
    {
        return '₹' . number_format($this->total_cost, 2);
    }

    public function getFormattedDiscountAmountAttribute()
    {
        return '₹' . number_format($this->discount_amount, 2);
    }

    public function getFormattedTaxAmountAttribute()
    {
        return '₹' . number_format($this->tax_amount, 2);
    }

    public function getReceiptStatusAttribute()
    {
        if ($this->quantity_received == 0) {
            return 'not_received';
        } elseif ($this->quantity_received < $this->quantity_ordered) {
            return 'partially_received';
        } else {
            return 'fully_received';
        }
    }

    public function getReceiptStatusBadgeAttribute()
    {
        $badges = [
            'not_received' => 'danger',
            'partially_received' => 'warning',
            'fully_received' => 'success'
        ];
        
        return $badges[$this->receipt_status] ?? 'secondary';
    }

    public function getReceiptStatusTextAttribute()
    {
        $texts = [
            'not_received' => 'Not Received',
            'partially_received' => 'Partially Received',
            'fully_received' => 'Fully Received'
        ];
        
        return $texts[$this->receipt_status] ?? 'Unknown';
    }

    // Methods
    public function calculateTotals()
    {
        $subtotal = $this->quantity_ordered * $this->unit_cost;
        $discountAmount = $subtotal * ($this->discount_percentage / 100);
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = $taxableAmount * ($this->tax_percentage / 100);
        $totalCost = $taxableAmount + $taxAmount;

        $this->update([
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_cost' => $totalCost,
            'quantity_pending' => $this->quantity_ordered - $this->quantity_received,
            'is_received' => $this->quantity_received >= $this->quantity_ordered
        ]);
    }

    public function receiveQuantity($quantity, $receivedDate = null)
    {
        $newReceivedQuantity = $this->quantity_received + $quantity;
        
        if ($newReceivedQuantity > $this->quantity_ordered) {
            throw new \Exception('Cannot receive more than ordered quantity');
        }

        $this->update([
            'quantity_received' => $newReceivedQuantity,
            'quantity_pending' => $this->quantity_ordered - $newReceivedQuantity,
            'is_received' => $newReceivedQuantity >= $this->quantity_ordered,
            'received_date' => $receivedDate ?? now()
        ]);

        // Update purchase totals
        $this->purchase->calculateTotals();
    }

    public function isFullyReceived()
    {
        return $this->quantity_received >= $this->quantity_ordered;
    }

    public function isPartiallyReceived()
    {
        return $this->quantity_received > 0 && $this->quantity_received < $this->quantity_ordered;
    }

    public function isNotReceived()
    {
        return $this->quantity_received == 0;
    }
}
