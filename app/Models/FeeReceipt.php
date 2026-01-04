<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeReceipt extends Model
{
    protected $fillable = [
        'receipt_number',
        'student_id',
        'fee_collection_id',
        'total_amount',
        'amount_paid',
        'balance_amount',
        'payment_method',
        'payment_reference',
        'receipt_date',
        'remarks',
        'generated_by',
        'is_cancelled',
        'cancellation_reason',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'receipt_date' => 'date',
        'is_cancelled' => 'boolean',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function feeCollection(): BelongsTo
    {
        return $this->belongsTo(FeeCollection::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_cancelled', false);
    }

    public function scopeCancelled($query)
    {
        return $query->where('is_cancelled', true);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('receipt_date', [$startDate, $endDate]);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    // Accessors
    public function getFormattedTotalAmountAttribute()
    {
        return '₹' . number_format($this->total_amount, 2);
    }

    public function getFormattedAmountPaidAttribute()
    {
        return '₹' . number_format($this->amount_paid, 2);
    }

    public function getFormattedBalanceAmountAttribute()
    {
        return '₹' . number_format($this->balance_amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_cancelled 
            ? '<span class="badge bg-danger">Cancelled</span>'
            : '<span class="badge bg-success">Active</span>';
    }

    public function getPaymentMethodLabelAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'bank_transfer' => 'Bank Transfer',
            'online' => 'Online',
            'upi' => 'UPI',
            'card' => 'Card',
            default => ucfirst($this->payment_method)
        };
    }
}
