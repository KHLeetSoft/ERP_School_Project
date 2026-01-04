<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCodePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'admin_id',
        'payment_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'gateway_transaction_id',
        'gateway_payment_id',
        'gateway_response',
        'qr_codes_purchased',
        'price_per_qr_code',
        'description',
        'paid_at',
        'expires_at',
        'failure_reason',
        'metadata'
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'metadata' => 'array',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
        'price_per_qr_code' => 'decimal:2'
    ];

    /**
     * Get the school that owns the payment.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the admin who made the payment.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Scope for pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Check if payment is expired.
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccessful()
    {
        return $this->status === 'completed';
    }

    /**
     * Mark payment as completed.
     */
    public function markAsCompleted($gatewayData = [])
    {
        $this->update([
            'status' => 'completed',
            'gateway_transaction_id' => $gatewayData['transaction_id'] ?? null,
            'gateway_payment_id' => $gatewayData['payment_id'] ?? null,
            'gateway_response' => $gatewayData,
            'paid_at' => now()
        ]);

        // Update school's QR code limit and payment status
        $this->school->update([
            'qr_code_limit' => $this->school->qr_code_limit + $this->qr_codes_purchased,
            'qr_limit_paid' => true,
            'qr_payment_amount' => $this->amount,
            'qr_payment_date' => now()
        ]);
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason
        ]);
    }

    /**
     * Generate unique payment ID.
     */
    public static function generatePaymentId()
    {
        return 'QR_' . time() . '_' . strtoupper(substr(md5(uniqid()), 0, 8));
    }
}