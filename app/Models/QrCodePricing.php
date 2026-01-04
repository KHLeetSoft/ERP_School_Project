<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCodePricing extends Model
{
    use HasFactory;

    protected $table = 'qr_code_pricing';

    protected $fillable = [
        'name',
        'description',
        'min_qr_codes',
        'max_qr_codes',
        'price_per_qr_code',
        'discount_percentage',
        'is_active',
        'sort_order',
        'features'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price_per_qr_code' => 'decimal:2',
        'discount_percentage' => 'decimal:2'
    ];

    /**
     * Scope for active pricing tiers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('min_qr_codes');
    }

    /**
     * Get the effective price per QR code after discount.
     */
    public function getEffectivePriceAttribute()
    {
        $discount = $this->price_per_qr_code * ($this->discount_percentage / 100);
        return $this->price_per_qr_code - $discount;
    }

    /**
     * Calculate total price for a given number of QR codes.
     */
    public function calculateTotalPrice($qrCodesCount)
    {
        if ($qrCodesCount < $this->min_qr_codes) {
            return null; // Not enough QR codes for this tier
        }

        if ($this->max_qr_codes && $qrCodesCount > $this->max_qr_codes) {
            return null; // Too many QR codes for this tier
        }

        return $qrCodesCount * $this->effective_price;
    }

    /**
     * Find the appropriate pricing tier for a given number of QR codes.
     */
    public static function findTierForQrCodes($qrCodesCount)
    {
        return static::active()
            ->where('min_qr_codes', '<=', $qrCodesCount)
            ->where(function($query) use ($qrCodesCount) {
                $query->whereNull('max_qr_codes')
                      ->orWhere('max_qr_codes', '>=', $qrCodesCount);
            })
            ->orderBy('min_qr_codes', 'desc')
            ->first();
    }

    /**
     * Get all available pricing tiers.
     */
    public static function getAvailableTiers()
    {
        return static::active()->ordered()->get();
    }
}