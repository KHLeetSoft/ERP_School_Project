<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QRCodeGenerator;

class QRCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

    protected $fillable = [
        'code',
        'title',
        'description',
        'type',
        'data',
        'url',
        'qr_image_path',
        'is_active',
        'scan_count',
        'expires_at',
        'created_by',
        'school_id',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Generate QR code image
     */
    public function generateQRImage($size = 200)
    {
        $qrData = $this->url ?: json_encode($this->data);
        
        $filename = 'qr_' . $this->code . '_' . time() . '.png';
        $path = 'public/qr-codes/' . $filename;
        
        // Generate QR code
        $qrCode = QRCodeGenerator::format('png')
            ->size($size)
            ->margin(2)
            ->generate($qrData);
        
        // Store the QR code image
        \Storage::put($path, $qrCode);
        
        $this->update(['qr_image_path' => $path]);
        
        return $path;
    }

    /**
     * Get QR code image URL
     */
    public function getQRImageUrlAttribute()
    {
        if ($this->qr_image_path) {
            return \Storage::url($this->qr_image_path);
        }
        return null;
    }

    /**
     * Check if QR code is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Increment scan count
     */
    public function incrementScanCount()
    {
        $this->increment('scan_count');
    }

    /**
     * Generate unique code
     */
    public static function generateUniqueCode($length = 8)
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, $length));
        } while (self::where('code', $code)->exists());
        
        return $code;
    }

    /**
     * Scope for active QR codes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}