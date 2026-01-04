<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'gateway_id',
        'price_type',
        'price',
        'billing_cycle',
        'features',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'plan_schools', 'payment_plan_id', 'school_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class, 'plan_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGateway($query, $gatewayId)
    {
        return $query->where('gateway_id', $gatewayId);
    }

    public function scopeByPriceType($query, $priceType)
    {
        return $query->where('price_type', $priceType);
    }

    public function scopeByBillingCycle($query, $billingCycle)
    {
        return $query->where('billing_cycle', $billingCycle);
    }
}
