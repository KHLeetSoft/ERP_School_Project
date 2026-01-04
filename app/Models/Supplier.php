<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'brand',
        'company',
        'contact_person',
        'email',
        'phone',
        'mobile',
        'address',
        'city',
        'state',
        'pincode',
        'country',
        'gst_number',
        'pan_number',
        'website',
        'credit_limit',
        'payment_terms_days',
        'status',
        'notes',
        'logo',
        'documents',
        'is_verified',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'documents' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'date'
    ];

    // Relationships
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'supplier_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('company', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('contact_person', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        $address = [];
        if ($this->address) $address[] = $this->address;
        if ($this->city) $address[] = $this->city;
        if ($this->state) $address[] = $this->state;
        if ($this->pincode) $address[] = $this->pincode;
        if ($this->country) $address[] = $this->country;
        
        return implode(', ', $address);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'success',
            'inactive' => 'warning',
            'suspended' => 'danger'
        ];
        
        return $badges[$this->status] ?? 'secondary';
    }

    public function getFormattedCreditLimitAttribute()
    {
        return '₹' . number_format($this->credit_limit, 2);
    }

    public function getVerificationStatusAttribute()
    {
        if ($this->is_verified) {
            return [
                'status' => 'verified',
                'badge' => 'success',
                'text' => 'Verified',
                'date' => $this->verified_at ? $this->verified_at->format('M d, Y') : null
            ];
        }
        
        return [
            'status' => 'unverified',
            'badge' => 'warning',
            'text' => 'Unverified',
            'date' => null
        ];
    }

    // Constants
    const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended'
    ];

    // Methods
    public function verify($verifiedBy = null)
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $verifiedBy ?? auth()->user()->name ?? 'Admin'
        ]);
    }

    public function unverify()
    {
        $this->update([
            'is_verified' => false,
            'verified_at' => null,
            'verified_by' => null
        ]);
    }

    public function getTotalPurchases()
    {
        return $this->inventoryItems()->sum(\DB::raw('quantity * price'));
    }

    public function getTotalItems()
    {
        return $this->inventoryItems()->count();
    }
}
