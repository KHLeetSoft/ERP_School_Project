<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'provider',
        'mode',
        'api_key',
        'api_secret',
        'webhook_url',
        'currency',
        'commission_rate',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2'
    ];

    protected $hidden = [
        'api_key',
        'api_secret'
    ];

    // Relationships
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(PaymentPlan::class);
    }

    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'gateway_schools', 'payment_gateway_id', 'school_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class, 'gateway_id');
    }

    // Accessors
    public function getDecryptedApiKeyAttribute()
    {
        return $this->api_key ? decrypt($this->api_key) : null;
    }

    public function getDecryptedApiSecretAttribute()
    {
        return $this->api_secret ? decrypt($this->api_secret) : null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeByMode($query, $mode)
    {
        return $query->where('mode', $mode);
    }
}
