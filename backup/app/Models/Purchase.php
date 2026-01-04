<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Purchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purchase_number',
        'supplier_id',
        'school_id',
        'purchase_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_cost',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'reference_number',
        'notes',
        'terms_conditions',
        'delivery_address',
        'billing_address',
        'prepared_by',
        'approved_by',
        'approved_at',
        'received_by',
        'received_at',
        'attachments',
        'is_active'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'approved_at' => 'date',
        'received_at' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'attachments' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('purchase_date', [$startDate, $endDate]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeOrdered($query)
    {
        return $query->where('status', 'ordered');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'secondary',
            'pending' => 'warning',
            'approved' => 'info',
            'ordered' => 'primary',
            'received' => 'success',
            'partially_received' => 'warning',
            'cancelled' => 'danger',
            'completed' => 'success'
        ];
        
        return $badges[$this->status] ?? 'secondary';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'partial' => 'info',
            'paid' => 'success',
            'overdue' => 'danger'
        ];
        
        return $badges[$this->payment_status] ?? 'secondary';
    }

    public function getFormattedTotalAttribute()
    {
        return '₹' . number_format($this->total_amount, 2);
    }

    public function getFormattedSubtotalAttribute()
    {
        return '₹' . number_format($this->subtotal, 2);
    }

    public function getFormattedPaidAmountAttribute()
    {
        return '₹' . number_format($this->paid_amount, 2);
    }

    public function getFormattedBalanceAttribute()
    {
        return '₹' . number_format($this->balance_amount, 2);
    }

    public function getIsOverdueAttribute()
    {
        return $this->expected_delivery_date && 
               $this->expected_delivery_date < now() && 
               !in_array($this->status, ['received', 'completed', 'cancelled']);
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->is_overdue) {
            return now()->diffInDays($this->expected_delivery_date);
        }
        return 0;
    }

    // Constants
    const STATUSES = [
        'draft' => 'Draft',
        'pending' => 'Pending',
        'approved' => 'Approved',
        'ordered' => 'Ordered',
        'received' => 'Received',
        'partially_received' => 'Partially Received',
        'cancelled' => 'Cancelled',
        'completed' => 'Completed'
    ];

    const PAYMENT_STATUSES = [
        'pending' => 'Pending',
        'partial' => 'Partial',
        'paid' => 'Paid',
        'overdue' => 'Overdue'
    ];

    const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'bank_transfer' => 'Bank Transfer',
        'cheque' => 'Cheque',
        'credit' => 'Credit',
        'other' => 'Other'
    ];

    // Methods
    public function generatePurchaseNumber()
    {
        $prefix = 'PO';
        $year = now()->year;
        $month = now()->format('m');
        $lastPurchase = self::whereYear('created_at', $year)
                          ->whereMonth('created_at', $month)
                          ->orderBy('id', 'desc')
                          ->first();
        
        $sequence = $lastPurchase ? (intval(substr($lastPurchase->purchase_number, -4)) + 1) : 1;
        
        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotals()
    {
        $subtotal = $this->purchaseItems->sum('total_cost');
        $taxAmount = $this->purchaseItems->sum('tax_amount');
        $discountAmount = $this->purchaseItems->sum('discount_amount');
        
        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $subtotal + $taxAmount + $this->shipping_cost - $discountAmount,
            'balance_amount' => ($subtotal + $taxAmount + $this->shipping_cost - $discountAmount) - $this->paid_amount
        ]);
    }

    public function approve($approvedBy = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approvedBy ?? auth()->user()->name ?? 'Admin',
            'approved_at' => now()
        ]);
    }

    public function markAsOrdered()
    {
        $this->update(['status' => 'ordered']);
    }

    public function markAsReceived($receivedBy = null)
    {
        $this->update([
            'status' => 'received',
            'received_by' => $receivedBy ?? auth()->user()->name ?? 'Admin',
            'received_at' => now(),
            'actual_delivery_date' => now()
        ]);

        // Update inventory stock
        foreach ($this->purchaseItems as $item) {
            if ($item->inventoryItem) {
                InventoryStockMovement::createMovement(
                    $item->inventory_item_id,
                    'in',
                    $item->quantity_received,
                    [
                        'movement_date' => now()->toDateString(),
                        'notes' => "Purchase received - {$this->purchase_number}",
                        'reference_type' => 'purchase',
                        'reference_id' => $this->id,
                        'reference_number' => $this->purchase_number,
                        'unit_cost' => $item->unit_cost,
                        'total_cost' => $item->total_cost,
                        'performed_by' => $receivedBy ?? auth()->user()->name ?? 'Admin',
                    ]
                );
            }
        }
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }
}
