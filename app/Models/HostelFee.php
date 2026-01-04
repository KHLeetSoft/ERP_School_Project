<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostelFee extends Model
{
    use HasFactory;

    protected $table = 'hostel_fees';

    protected $fillable = [
        'school_id',
        'allocation_id',
        'month',
        'year',
        'amount',
        'due_date',
        'status',
        'payment_date',
        'payment_method',
        'transaction_id',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
    ];

    // Constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_WAIVED = 'waived';

    // Relationships
    public function allocation(): BelongsTo
    {
        return $this->belongsTo(HostelAllocation::class, 'allocation_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE);
    }

    public function scopeWaived($query)
    {
        return $query->where('status', self::STATUS_WAIVED);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    // Methods
    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isOverdue()
    {
        return $this->status === self::STATUS_OVERDUE || 
               ($this->status === self::STATUS_PENDING && $this->due_date < now());
    }

    public function markAsPaid($paymentMethod = null, $transactionId = null)
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'payment_date' => now(),
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
        ]);
    }

    public function markAsOverdue()
    {
        if ($this->status === self::STATUS_PENDING && $this->due_date < now()) {
            $this->update(['status' => self::STATUS_OVERDUE]);
        }
    }

    public function getMonthName()
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getFormattedAmount()
    {
        return '₹' . number_format($this->amount, 2);
    }
}
