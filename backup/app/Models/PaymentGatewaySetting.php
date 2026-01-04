<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentGatewaySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'gateway_name',
        'display_name',
        'is_active',
        'is_test_mode',
        'api_credentials',
        'supported_payment_methods',
        'transaction_fee_percentage',
        'minimum_amount',
        'maximum_amount',
        'webhook_url',
        'return_url',
        'cancel_url',
        'additional_settings',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
        'api_credentials' => 'array',
        'supported_payment_methods' => 'array',
        'transaction_fee_percentage' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'additional_settings' => 'array'
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper methods
    public function isGatewayActive(): bool
    {
        return $this->is_active;
    }

    public function getApiKey($key): ?string
    {
        return $this->api_credentials[$key] ?? null;
    }

    public function supportsPaymentMethod($method): bool
    {
        return in_array($method, $this->supported_payment_methods ?? []);
    }
}
