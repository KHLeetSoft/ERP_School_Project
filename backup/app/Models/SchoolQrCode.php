<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolQrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'qr_type',
        'title',
        'description',
        'upi_id',
        'merchant_name',
        'amount',
        'qr_code_data',
        'qr_code_image',
        'is_active',
        'usage_count',
        'additional_data',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'usage_count' => 'integer',
        'additional_data' => 'array'
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
    public function canBeUsed(): bool
    {
        return $this->is_active;
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    // Check if school already has a QR code
    public static function schoolHasQrCode($schoolId): bool
    {
        return self::where('school_id', $schoolId)->exists();
    }

    // Get school QR code
    public static function getSchoolQrCode($schoolId)
    {
        return self::where('school_id', $schoolId)->first();
    }
}